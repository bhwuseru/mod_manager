<?php

use App\Models\CategoryModMetafile;
use App\Models\ModMetafile;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

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
    public boolean $hasCustomURL = false;
    public CarbonInterface $lastNexusQuery;
    public CarbonInterface $lastNexusUpdate;
    public CarbonInterface  $nexusLastModified;
    public boolean $converted = false;
    public boolean $validated = false;
    public string $color = "";
    public int $tracked;
    public int $endorsed;
    public int $fileid;
    public int $size;
    public Model $modMetafile = new ModMetafile;
    public $categoryModMetafiles = [];


    public function __construct(string $path, $parentDirName)
    {

        $this->parentDirName = $parentDirName;
        $metaIniContent = file_get_contents($path);
        $lines = explode("\n", $metaIniContent);

        foreach ($lines as $line) {
            // 行を解析してキーと値を取得
            $parts = explode('=', $line, 2);

            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);

                if($key === 'category') {
                    $categories = explode(',', $value);
                    $this->$key = $categories;
                    $sortno = 0;
                    foreach($categories as $categoryId) {
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
    }

    public function save() {
        $this->modMetafile->save();
        foreach($this->categoryModMetafiles as $file) {
            $count = CategoryModMetafile::where('directory_name', $file->directory_name)->count();
            if(0 < $count) continue;

            $file->mod_metafile_id = $this->modMetafile->id;
            $file->save();
        }
    }
}
