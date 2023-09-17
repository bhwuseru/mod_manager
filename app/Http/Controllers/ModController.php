<?php

namespace App\Http\Controllers;

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
            fputcsv($csvFileHandle, ['mod_directory', 'mod_name']);

            $modDirs = DirectoryService::getDirs($modsPath);
            foreach($modDirs as $dir) {
                $modDirPath =  $modsPath . DIRECTORY_SEPARATOR. $dir;
                $pluginFiles = DirectoryService::getFiles($modDirPath, ['esp', 'esl', 'esm']);
                $mods[$dir] = $pluginFiles;

                foreach($pluginFiles as $file) {
                                fputcsv($csvFileHandle, [
                                    'mod_directory' => $dir,
                                    'mod_name' =>  $file,
                        ]);
                }
            }

            return view('mod.index', compact('mods'));
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
