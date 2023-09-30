<?php

namespace App\Domains;

use App\Services\DirectoryService;
use Directory;
use Exception;

class DownloadDomain {

    public string $installationFile;
    public $metafileInfo = [];
    public string $modName = '';
    public string $name = '';
    public function __construct(string $downloadPath,  string $installationFile)
    {
        $this->installationFile = $installationFile;

        if(!is_dir($downloadPath)) throw new Exception('DownloadDomain construct Error not a directory: '. $downloadPath);
        $pathInfo = DirectoryService::searchFiles($downloadPath, $installationFile);

        if(!isset($pathInfo['basename'])) {
            $this->metafileInfo = $pathInfo;
            return;
        }

        $metafile = $pathInfo['filename'] . '.meta';
        $pathInfo = DirectoryService::searchFiles($downloadPath, $metafile);
        if(isset($pathInfo['basename'])) {
            $file = $this->getModName($pathInfo['dirname'] . DIRECTORY_SEPARATOR . $pathInfo['basename']);
            $this->modName = $file['modName'];
            $this->name = $file['name'];
        }
    }

    private function getModName(string $metafilePath): array {
        try {
            $metaIniContent = file_get_contents($metafilePath);
            $lines = explode("\n", $metaIniContent);
            $file = [];
            foreach ($lines as $line) {
                // 行を解析してキーと値を取得
                $parts = explode('=', $line, 2);
                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    if ($key === 'name') {
                        $file['name'] = $value;
                    }
                    if($key == 'modName') {
                        $file['modName'] = $value;
                    }
                }
            }
            return $file;

        } catch (Exception $e) {
            // エラーが発生した場合、ログに記録
            Log::error($e);
            return false;
        }
    }
}
