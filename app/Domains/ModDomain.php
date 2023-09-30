<?php

namespace App\Domains;

use App\Models\CategoryModMetafile;
use App\Models\Mod;
use App\Models\Plugin;
use App\Services\DirectoryService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Catch_;

use function PHPUnit\Framework\fileExists;

class ModDomain
{
    // プラグインファイルのリスト
    public array $pluginFiles = [];

    // ディレクトリ名
    private string $directoryName = '';

    // MetaFileDomain インスタンスまたは null
    public MetaFileDomain | null $metafile;

    // Caliente Tools の情報
    public bool $containClientTools = false;
    public array $categories = [];

    // セパレーターが存在するかどうかのフラグ
    public bool $isSeparator = false;
    public string $modName = '';
    public string $name = '';
    public DownloadDomain $downloadDomain;

    /**
     * ModDomain コンストラクタ
     *
     * @param string $dirpath ディレクトリのパス
     */
    public function __construct(string $dirpath)
    {
        // ディレクトリ名を生成
        $this->directoryName = $this->createDirectoryName($dirpath);

        // meta.ini ファイルが存在する場合、MetaFileDomain インスタンスを作成
        $this->metafile = null;
        if (fileExists($dirpath . DIRECTORY_SEPARATOR . 'meta.ini')) {
            $this->metafile = new MetaFileDomain($dirpath);
        }
        if (fileExists($dirpath . DIRECTORY_SEPARATOR . 'CalienteTools')) {
            if (is_dir($dirpath . DIRECTORY_SEPARATOR . 'CalienteTools')) {
                $this->containClientTools = true;
            }
        }

        // プラグインファイルのリストを取得
        $pluginFiles = DirectoryService::getIncludedExtensionFiles($dirpath, ['esp', 'esl', 'esm']);
        foreach ($pluginFiles as $file) {
            // $plugin = new PluginDomain;
            $plugin = new Plugin;
            $plugin->directory_name = $this->directoryName;
            $plugin->plugin_name = $file['filename'];
            $plugin->extension = $file['extension'];
            $plugin->basename = $file['basename'];

            array_push($this->pluginFiles, $plugin);
        }
    }

    public function setDownload(string $downloadPath) {
        $this->downloadDomain = new DownloadDomain($downloadPath, $this->metafile->installationFile);
    }

    /**
     * ディレクトリ名を取得
     *
     * @return string ディレクトリ名
     */
    public function getDirectoryName(): string
    {
        return $this->directoryName;
    }

    /**
     * MetaFileDomain インスタンスを取得
     *
     * @return MetaFileDomain|null MetaFileDomain インスタンスまたは null
     */
    public function getMetafile()
    {
        return $this->metafile;
    }

    /**
     * ディレクトリ名を生成し、セパレーターを取り除く
     *
     * @param string $dirPath ディレクトリのパス
     * @return string 生成されたディレクトリ名
     */
    private function createDirectoryName(string $dirPath): string
    {
        $ending = "_separator";
        $dirName = basename($dirPath);

        // ディレクトリ名がセパレーターで終わっている場合、セパレーターを取り除く
        if (substr($dirName, -strlen($ending)) === $ending) {
            $trimmedSeparator = rtrim($dirName, "_separator");
            $this->isSeparator = true;
            $dirName = $trimmedSeparator;
        }

        return $dirName;
    }

    public function register()
    {
        try {
            DB::transaction(function () {
                $mod = Mod::where('directory_name', $this->directoryName)->get();
                if($mod->isNotEmpty()) {
                    $mod->delete();
                }
                Mod::create([
                    'directory_name' => $this->directoryName,
                    'contain_client_tools' => $this->containClientTools,
                    'is_separator' => $this->isSeparator,
                ]);

                $pliginFiles = Plugin::where('directory_name')->get();
                if($pliginFiles->isNotEmpty()){
                    $pliginFiles->delete();
                }
                foreach($this->pluginFiles as $plugin) {
                    $plugin->save();
                }

                $categoris = CategoryModMetafile::where('directory_name', $this->directoryName)->get();
                if ($categoris->isNotEmpty()) {
                    $categoris->delete();
                }
                if (!is_null($this->metafile)) {
                    $this->metafile->register();
                }

            });
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

}
