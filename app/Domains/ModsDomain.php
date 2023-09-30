<?php
namespace App\Domains;

use App\Services\CsvReaderService;
use App\Services\DirectoryService;
use Exception;
use Illuminate\Support\Facades\Log;

class ModsDomain {

    public $mods = [];

    public function __construct(string $modsPath, string $downloadPath = '')
    {
          try {
            // 準備処理
            // $publicPath = public_path() . DIRECTORY_SEPARATOR . 'storage';
            // $modsPath = $publicPath . DIRECTORY_SEPARATOR . 'mods';
            // $categoryCsv = public_path() . DIRECTORY_SEPARATOR . 'category.csv';

            // $categoryDomain = new CategoryDomain;
            // $categoryDomain->postCsvData($categoryCsv);

            $modDirs = DirectoryService::getDirs($modsPath);
            foreach ($modDirs as $dir) {
                $modDirPath =  $modsPath . DIRECTORY_SEPARATOR . $dir;
                $mod = new ModDomain($modDirPath);
                if(!empty($downloadPath)) {
                    $mod->setDownload($downloadPath);
                }
                array_push($this->mods, $mod);
            }

        } catch (Exception $e) {
            Log::error($e);
        }

    }

    public function findModName(string $directoryName): ModDomain | null {

        foreach($this->mods as $mod) {
            if(!$mod instanceof ModDomain) break;
            if($mod->modName == $directoryName) return $mod;
        }

        return null;
    }

}
