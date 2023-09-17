<?php

namespace App\Http\Controllers;

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
            $csvFile = public_path('resouces/modlist.csv');
            $publicPath = public_path() . '/storage';
            $modsPath = $publicPath . '/' . 'mods'; // モッドディレクトリのパス（storage/app以下）
            $modsDirectories = array_diff(scandir($modsPath), ['.', '..']);
            $modDirs = [];

            $csvOutputFile = 'mod_directory_list.csv';
            $csvFileHandle = fopen($csvOutputFile, 'w');
            // ヘッダ行をCSVファイルに書き込む
            fputcsv($csvFileHandle, ['mod_directory', 'mod_name']);

            foreach ($modsDirectories as $directory) {
                // ディレクトリの絶対パスを取得
                $directoryPath = $modsPath . '/' . $directory;
                // ディレクトリかどうかを確認し、ディレクトリであれば表示
                if (is_dir($directoryPath)) {
                    $modDirInfo = [];
                    $inModDirictories =  array_diff(scandir($directoryPath), ['.', '..']);
                    // modディレクトリ内検証
                    foreach ($inModDirictories as $inModDirictory) {
                        $inModDirictoryPath  = $directoryPath . '/' . $inModDirictory;

                        if (!is_dir($inModDirictoryPath)) {
                            $modFileNameWithExtension = pathinfo($inModDirictory, PATHINFO_FILENAME);
                            $modFileExtension = pathinfo($inModDirictory, PATHINFO_EXTENSION);

                            // ファイルの拡張子が esp または esl または esm の場合にCSVファイルに書き込む
                            if (in_array($modFileExtension, ['esp', 'esl', 'esm'])) {
                                fputcsv($csvFileHandle, [$inModDirictory, $modFileNameWithExtension]);
                            }
                        }
                    }
                    array_push($modDirs, $directory);
                }
            }

            return view('mod.index', compact('modDirs'));
        } catch (Exception $e) {
            Log::error($e);
            // return redirect()->route('mods')->with([
            //     'error' => $e->getMessage()
            // ]);
        }
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
