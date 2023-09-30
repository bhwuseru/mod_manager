<?php

namespace App\Livewire;

use App\Domains\CategoryDomain;
use App\Domains\ModDomain;
use App\Domains\ModsDomain;
use App\Domains\ModViewDomain;
use App\Http\Requests\ModSearchRequest;
use App\Models\Mod;
use App\Models\Plugin;
use App\Services\CsvReaderService;
use App\Services\DirectoryService;
use Egulias\EmailValidator\Result\Reason\UnclosedComment;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

use function PHPUnit\Framework\fileExists;

class SearchMods extends Component
{
    public $searchModName = '';
    public $calienteTools = 'CalienteTools';

    public function render()
    {
        try {
            // 準備処理
            // $csvFile = public_path('resouces/modlist.csv');
            $publicPath = public_path() . DIRECTORY_SEPARATOR . 'storage';
            $modsPath = $publicPath . DIRECTORY_SEPARATOR . 'mods';
            $categoryCsv = public_path() . DIRECTORY_SEPARATOR . 'category.csv';

            $categoryDomain = new CategoryDomain;
            $categoryDomain->postCsvData($categoryCsv);

            $mods = [];
            $modDirs = DirectoryService::getDirs($modsPath);
            foreach ($modDirs as $dir) {
                $modDirPath =  $modsPath . DIRECTORY_SEPARATOR . $dir;
                $mod = new ModDomain($modDirPath);
                array_push($mods, $mod);
            }

            return view('livewire.search-mods', [
                'mods' => $mods,
            ]);
        } catch (Exception $e) {
            Log::error($e);
        }
    }


    public function batch() {
        try {
            // 準備処理
            $csvFile = public_path('resources/modlist.csv');
            $downloadPath = public_path('downloads');
            $csvContents = (new CsvReaderService())->readCsvFile($csvFile);
            $publicPath = public_path() . DIRECTORY_SEPARATOR . 'storage';
            $modsPath = $publicPath . DIRECTORY_SEPARATOR . 'mods';
            $modsDoamin = new ModsDomain($modsPath, $downloadPath);
            $categoryCsv = public_path() . DIRECTORY_SEPARATOR . 'category.csv';

            $categoryDomain = new CategoryDomain;
            $categoryDomain->postCsvData($categoryCsv);
            $activePlugins = [];
            $notPluginFiles = [];

            foreach($csvContents as $row) {
                $mod = $modsDoamin->findModName($row['Mod_Name']);
                if(is_null($mod)) {
                    array_push($notPluginFiles[$row['Mod_Name']]);
                    continue;
                }

                foreach($mod->pluginFiles as $plugin) {
                    if(!$plugin instanceof Plugin) continue;
                    array_push($activePlugins, $plugin->basename);
                }
            }
            $modDirs = DirectoryService::getDirs($modsPath);
            $notPluginFiles = [];
            $extistPluginFiles = [];
            // foreach ($modDirs as $dir) {
            //     $modDirPath =  $modsPath . DIRECTORY_SEPARATOR . $dir;
            //     $mod = new ModDomain($modDirPath);
            //     $test = [];
            //     foreach($csvContents as $row) {
            //         if($row['Mod_Name'] == $mod->getDirectoryName()){
            //             dd($row);
            //         }
            //     }
                // if(!empty($mod->pluginFiles)) {
                //     foreach($mod->pluginFiles as $plugin) {
                //         // $plugin->plugin_nam;
                //         $exists = false;
                //         $plugin_name = $plugin->plugin_name;
                //         foreach($csvContents as $row) {
                //             if($row['Mod_Name'] ==  $plugin_name) {
                //                 array_push($extistPluginFiles, $row['basename']);
                //                 $exists = true;
                //                 break;
                //             }
                //         }
                //         if(!$exists) {
                //             array_push($notPluginFiles, $$plugin_name);
                //         }
                //     }
                // }

                // if(!empty($notPluginFiles)) {
                //     // 配列の要素を改行で結合して文字列にする
                //     $content = implode(PHP_EOL, $data);

                //     // ファイルに書き込む
                //     $res = file_put_contents( public_path('resources/not_plugin.txt'), $content);
                // }

                // $this->mods[$dir] = $mod;
                // $mod->register();
            // }

            // return view('livewire.search-mods');
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function getMetaFiles()
    {
    }
}
