<?php

namespace App\Livewire;

use App\Domains\Mod as DomainsMod;
use App\Http\Requests\ModSearchRequest;
use App\Services\DirectoryService;
use Livewire\Component;
use MetaFile;

class Mod extends Component
{
    public $search = '';
    // public $csvFile = public_path('resouces/modlist.csv');
    // public $publicPath = public_path() . '/storage';
    // public $modsPath = $publicPath . '/' . 'mods';

    public function render()
    {
        return view('livewire.mod');
    }

    public function batch() {
        // $csvFile = public_path('resouces/modlist.csv');
        $publicPath = public_path() . '/storage';
        $modsPath = $publicPath . '/' . 'mods';

        $modDirs = DirectoryService::getDirs($modsPath);
        $mods = [];
        foreach ($modDirs as $dir) {
            $modDirPath =  $this->modsPath . DIRECTORY_SEPARATOR . $dir;
            $metafile = new MetaFile($modDirPath, $dir);
            $pluginFiles = DirectoryService::getIncludedExtensionFiles($modDirPath, ['esp', 'esl', 'esm']);
            $mod = new DomainsMod();
            $mod->metafile = $metafile;
            $mod->pluginFiles = $pluginFiles;
            $mod->directoryName = $dir;

            $mods[$dir] = $pluginFiles;
        }
    }

    public function getMetaFiles() {

    }
    // public function search(ModSearchRequest $request) {
    public function search() {
        try {
            // 準備処理
            $mods = [];
            $csvFile = public_path('resouces/modlist.csv');
            $publicPath = public_path() . '/storage';
            $modsPath = $publicPath . '/' . 'mods';

            // csv準備処理
            $csvOutputFile = 'mod_directory_list.csv';
            $csvFileHandle = fopen($csvOutputFile, 'w');
            // ヘッダ行をCSVファイルに書き込む
            // fputcsv($csvFileHandle, ['mod_directory', 'mod_name']);

            $modDirs = DirectoryService::getDirs($modsPath);
            foreach ($modDirs as $dir) {
                $modDirPath =  $modsPath . DIRECTORY_SEPARATOR . $dir;
                $pluginFiles = DirectoryService::getIncludedExtensionFiles($modDirPath, ['esp', 'esl', 'esm']);
                $mods[$dir] = $pluginFiles;
            }

            return view('mod.index', compact('mods'));
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
