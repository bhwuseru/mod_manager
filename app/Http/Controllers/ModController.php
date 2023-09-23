<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModListCsvStoreRequest;
use App\Services\DirectoryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ModController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // 準備処理
            $csvFile = public_path('resouces/modlist.csv');
            $publicPath = public_path() . '/storage';
            $modsPath = $publicPath . '/' . 'mods';
            $mods = [];

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

    public function extractMissingPlugins(ModListCsvStoreRequest $request)
    {
        try {
            // リクエストからファイルを取得
            $file = $request->file('csv_file');
            // アップロードされたファイルのMIMEタイプを取得
            $mimeType = $file->getMimeType();

            // MIMEタイプがCSVファイルかどうかを確認
            // CSVファイルでない場合のエラーハンドリング
            if ($mimeType != 'text/csv' || $mimeType != 'application/vnd.ms-excel') {
                throw new Exception('CSVファイルをアップロードしてください');
            }

            // 準備処理
            $csvFile = public_path('resouces/modlist.csv');
            $publicPath = public_path() . '/storage';
            $modsPath = $publicPath . '/' . 'mods';
            $mods = [];

            // csv準備処理
            $csvOutputFile = 'mod_directory_list.csv';
            $csvFileHandle = fopen($csvOutputFile, 'w');
            // ヘッダ行をCSVファイルに書き込む
            // fputcsv($csvFileHandle, ['mod_directory', 'mod_name']);

            $modDirs = DirectoryService::getDirs($modsPath);
            foreach ($modDirs as $dir) {
                $modDirPath =  $modsPath . DIRECTORY_SEPARATOR . $dir;
                $pluginFiles = DirectoryService::getFiles($modDirPath, ['esp', 'esl', 'esm']);
                $mods[$dir] = $pluginFiles;
            }


            $modList = $this->getModlist();
            foreach ($modList as $mod) {
                $pluginFile = $mod['Mod_Name'];
                dd($pluginFile);
            }
            foreach ($modDirs as $dirName => $pluginFiles) {
                foreach ($pluginFiles as $file) {
                }
            }

            //  return view('mod.index', compact('mods'));


        } catch (Exception $e) {
            return redirect()->route('mods')->with([
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getModlist(): array
    {
        // CSVファイルのパス
        $csvFilePath = public_path('resouces/modlist.csv');
        // CSVファイルを読み込む
        $csvFile = fopen($csvFilePath, 'r');

        if ($csvFile === false) {
            // ファイルが開けなかった場合のエラーハンドリング
            throw new Exception('CSVファイルを開けませんでした');
        }

        $modList = [];

        // ヘッダー行をスキップ
        fgetcsv($csvFile);

        // CSVファイルを行ごとに読み込み
        while (($data = fgetcsv($csvFile)) !== false) {
            $modPriority = $data[0];
            $modName = $data[1];

            // データを配列に追加
            $modList[] = [
                'Mod_Priority' => $modPriority,
                'Mod_Name' => $modName,
            ];
        }
        fclose($csvFile);
        return $modList;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
