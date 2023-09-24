<?php

namespace App\Livewire;

use App\Domains\ModDomain;
use App\Http\Requests\ModSearchRequest;
use App\Models\Mod;
use App\Services\DirectoryService;
use Egulias\EmailValidator\Result\Reason\UnclosedComment;
use Livewire\Component;

use function PHPUnit\Framework\fileExists;

class SearchMods extends Component
{
    public $searchModName = '';
    public $calienteTools = 'CalienteTools';
    public $mods = [];

    public function render()
    {
        try {
            // 準備処理
            // $csvFile = public_path('resouces/modlist.csv');
            $publicPath = public_path() . '/storage';
            $modsPath = $publicPath . '/' . 'mods';

            // csv準備処理
            // $csvOutputFile = 'mod_directory_list.csv';
            // $csvFileHandle = fopen($csvOutputFile, 'w');
            // ヘッダ行をCSVファイルに書き込む
            // fputcsv($csvFileHandle, ['mod_directory', 'mod_name']);

            $this->mods = Mod::all();
            // $modDirs = DirectoryService::getDirs($modsPath);
            // if(1 > count($modDirs)) return view('livewire.search-mods', 'mods');

            // foreach ($modDirs as $dir) {
            //     $modDirPath =  $modsPath . DIRECTORY_SEPARATOR . $dir;
            //     $mod = new ModDomain($modDirPath);
            //     $this->mods[$dir] = $mod;
            // }

            return view('livewire.search-mods');
        } catch (Exception $e) {
            Log::error($e);
        }
    }


    public function batch() {
        try {
            // 準備処理
            // $csvFile = public_path('resouces/modlist.csv');
            $publicPath = public_path() . '/storage';
            $modsPath = $publicPath . '/' . 'mods';

            // csv準備処理
            // $csvOutputFile = 'mod_directory_list.csv';
            // $csvFileHandle = fopen($csvOutputFile, 'w');
            // ヘッダ行をCSVファイルに書き込む
            // fputcsv($csvFileHandle, ['mod_directory', 'mod_name']);

            $modDirs = DirectoryService::getDirs($modsPath);
            foreach ($modDirs as $dir) {
                $modDirPath =  $modsPath . DIRECTORY_SEPARATOR . $dir;
                $mod = new ModDomain($modDirPath);
                // $this->mods[$dir] = $mod;
                $mod->register();
            }

            // return view('livewire.search-mods');
        } catch (Exception $e) {
            Log::error($e);
        }
    }
    // public function batch()
    // {
    //     // $csvFile = public_path('resouces/modlist.csv');
    //     $publicPath = public_path() . '/storage';
    //     $modsPath = $publicPath . '/' . 'mods';

    //     $modDirs = DirectoryService::getDirs($modsPath);
    //     $mods = [];
    //     foreach ($modDirs as $dir) {
    //         $modDirPath =  $modsPath . DIRECTORY_SEPARATOR . $dir;
    //         $metafile = new MetaFile($modDirPath, $dir);
    //         $pluginFiles = DirectoryService::getIncludedExtensionFiles($modDirPath, ['esp', 'esl', 'esm']);
    //         $mod = new DomainsMod();
    //         $mod->metafile = $metafile;
    //         $mod->pluginFiles = $pluginFiles;
    //         $mod->setDirectoryName($dir);

    //         $mods[$dir] = $pluginFiles;
    //     }
    // }

    public function getMetaFiles()
    {
    }
}
