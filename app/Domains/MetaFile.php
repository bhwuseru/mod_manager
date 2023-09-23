<?php

namespace App\Domains;

use App\Models\CategoryModMetafile;
use App\Models\ModMetafile;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MetaFile
{

    public string $parentDirName;
    public string $gameName;
    public int $modid;
    public string $version = "";
    public string $newestVersion = "";
    public array $category = [];
    public string $nexusFileStatus = "";
    public string $installationFile = "";
    public string $repository = "";
    public string $ignoredVersion = "";
    public string $comments = "";
    public string $notes = "";
    public string $nexusDescription = "";
    public string $url = "";
    public bool $hasCustomURL = false;
    public CarbonInterface $lastNexusQuery;
    public CarbonInterface $lastNexusUpdate;
    public CarbonInterface  $nexusLastModified;
    public bool $converted = false;
    public bool $validated = false;
    public string $color = "";
    public int $tracked;
    public int $endorsed;
    public int $fileid;
    public int $size;
    public ModMetafile | null $modMetafile = null;
    public $categoryModMetafiles = [];


    public function __construct(string $path, $parentDirName)
    {

        $this->parentDirName = $parentDirName;
        $success = $this->createMetaFile($path, $parentDirName);
        if (!$success) {
            $this->modMetafile = null;
        }
    }

    private function createMetaFile(string $path, string $parentDirName): bool
    {
        $metafilePath = $path . DIRECTORY_SEPARATOR . 'meta.ini';
        if (!file_exists($metafilePath)) return false;

        try {
            $metaIniContent = file_get_contents($path);
            $lines = explode("\n", $metaIniContent);
            $this->modMetafile = new ModMetafile;

            foreach ($lines as $line) {
                // 行を解析してキーと値を取得
                $parts = explode('=', $line, 2);

                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);

                    if ($key === 'category') {
                        $categories = explode(',', $value);
                        $this->$key = $categories;
                        $sortno = 0;
                        foreach ($categories as $categoryId) {
                            $sortno++;
                            $categoryModMetafie = new CategoryModMetafile;
                            $categoryModMetafie->directory_name = $parentDirName;
                            $categoryModMetafie->sortno = $sortno;
                            $categoryModMetafie->category_id = $categoryId;
                            $this->categoryModMetafiles = [
                                ...$this->categoryModMetafiles,
                                $categoryModMetafie,
                            ];
                        }
                        continue;
                    }

                    // 1\ を除去し、fileid を修正
                    $key = preg_replace('/^1\\\/', '', $key);
                    // カラム名をローワーキャメルケースからスネークケースに変換
                    $key = Str::snake($key);
                    $this->$key = $value;
                    $this->modMetafile->$key = $value;
                }
            }

            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public function save()
    {
        if(is_null($this->modMetafile)) return;

        $this->modMetafile->save();
        foreach ($this->categoryModMetafiles as $file) {
            $count = CategoryModMetafile::where('directory_name', $file->directory_name)->count();
            if (0 < $count) continue;

            $file->mod_metafile_id = $this->modMetafile->id;
            $file->save();
        }
    }
}
