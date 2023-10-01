<?php

namespace App\Domains;

use App\Services\DirectoryService;
use Exception;

class DownloadDomain {

public string $installationFile;
public $metafileInfo = [];
public string $name = '';
public string $modName;
public string $gameName;
public int $modID;
public int $fileID;
public string $url;
public string $description;
public string $version;
public string $newestVersion;
public string $fileTime;
public int $fileCategory;
public int $category;
public string $repository;
public string $installed;
public string $uninstalled;
public string $paused;
public string $removed;
public string $metaFilePath = '';
public string $downloadPath = '';

public function __construct(string $downloadPath,  string $installationFile)
    {
        $this->installationFile = $installationFile;

        if(!is_dir($downloadPath)) throw new Exception('DownloadDomain construct Error not a directory: '. $downloadPath);

        $pathInfo = pathinfo($installationFile);
        if(!empty($pathInfo)) {
            $metafile = $pathInfo['filename'] . '.meta';
            echo 'metafile: ' . $metafile . PHP_EOL;
            $pathInfo = DirectoryService::searchFiles($downloadPath, $metafile);
            $this->metaFilePath  = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['basename'];
            $this->downloadPath =  $pathInfo['dirname'];
            $res = $this->setProperty($this->metaFilePath);
        }
    }

    private function setProperty(string $metafilePath): bool {
        try {
            $metaIniContent = file_get_contents($metafilePath);
            $lines = explode("\n", $metaIniContent);
            echo $lines;
            foreach ($lines as $line) {
                // 行を解析してキーと値を取得
                $parts = explode('=', $line, 2);
                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    if('modName')
                    if(isset($this->$key)) {
                        $this->$key = $value;
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            // エラーが発生した場合、ログに記録
            Log::error($e);
            return false;
        }
    }

    public function exportCSVContent($csvFilePath) {


    }
}
