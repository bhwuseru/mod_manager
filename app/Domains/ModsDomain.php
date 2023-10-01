<?php
namespace App\Domains;

use App\Services\CsvReaderService;
use App\Services\CsvWriterService;
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
                $mod->register();

                if(!empty($downloadPath)) {
                    if(empty($mod->metafile->modMetafile)) continue;
                    if(is_null($mod->metafile) ) continue;
                    if(empty($mod->metafile->installationFile)) continue;

                    echo $mod->metafile->installationFile . PHP_EOL;
                    // $mod->setDownload($downloadPath, $mod->metafile->installationFile);
                    // $csv_header = [
                    //     'mod_name',
                    //     'mod_dir_path',
                    //     'mod_installation_file',
                    //     'mod_download_path',
                    //     'mod_directory_name',
                    // ];
                    // if(file_exists(public_path('mods.csv')))
                    // $csv = new CsvWriterService(public_path('test.csv'));

                    // $data = $mod->downloadDomain->modName . ',' .
                    // $mod->modDirPath . ',' .
                    // $mod->metafile->installationFile . ',' .
                    // $mod->downloadDomain->downloadPath . ',' .
                    // $mod->getDirectoryName();

                    // $csv->appendToCsv($data, $csv_header);
                }
                array_push($this->mods, $mod);
            }

        } catch (Exception $e) {
            Log::error($e);
        }

    }

    public function register() {
        foreach ($this->mods as $mod) {
            $mod->register();
        }
    }

    // public function findModName(string $directoryName): ModDomain | null {

    //     foreach($this->mods as $mod) {
    //         if(!$mod instanceof ModDomain) break;
    //         if($mod->modName == $directoryName) return $mod;
    //     }

    //     return null;
    // }

}
