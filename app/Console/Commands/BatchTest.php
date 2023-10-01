<?php

namespace App\Console\Commands;

use App\Domains\CategoryDomain;
use App\Domains\ModsDomain;
use App\Services\CsvReaderService;
use Illuminate\Console\Command;

class BatchTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:batch-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // 準備処理
            $csvFile = public_path('resources/modlist.csv');
            $downloadPath = public_path('downloads');
            $csvContents = (new CsvReaderService())->readCsvFile($csvFile);
            $publicPath = public_path() . DIRECTORY_SEPARATOR . 'storage';
            $modsPath = $publicPath . DIRECTORY_SEPARATOR . 'mods';
            $modsDoamin = new ModsDomain($modsPath, $downloadPath);
            // $modsDoamin->register();
            $categoryCsv = public_path() . DIRECTORY_SEPARATOR . 'category.csv';

            $categoryDomain = new CategoryDomain;
            $categoryDomain->postCsvData($categoryCsv);
            $activePlugins = [];
            $notPluginFiles = [];
            $activeMods = [];

            // foreach($csvContents as $row) {
            //     $mod = $modsDoamin->findModName($row['Mod_Name']);
            //     if(is_null($mod)) {
            //         array_push($notPluginFiles[$row['Mod_Name']]);
            //         continue;
            //     }
            //     $activeMods[] = $mod;

            //     // foreach($mod->pluginFiles as $plugin) {
            //     //     if(!$plugin instanceof Plugin) continue;
            //     //     array_push($activePlugins, $plugin->basename);
            //     // }

            // }
            // $newContent = '';
            // foreach($activeMods as $mod) {
            //     $newContent .= $mod->getDirectoryName() . PHP_EOL;
            //     // ファイルに新しい内容を追加する
            // }
            // if(!empty($newContent)) {
            //     $result = file_put_contents(public_path('output.txt'), $newContent, FILE_APPEND);
            // }

        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
