<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class DirectoryService
{

    /**
     * 指定されたディレクトリ内のディレクトリ一覧を取得します。
     *
     * @param string $path ディレクトリのパス
     * @return array ディレクトリ名の配列
     */
    public static function getDirs(string $path): array
    {
        $dirs = [];
        try{
            // ディレクトリ内のファイルおよびディレクトリの一覧を取得
            $directories =  array_diff(scandir($path), ['.', '..']);
            foreach ($directories as $directory) {
                $directoryPath = $path . DIRECTORY_SEPARATOR . $directory;
                // ディレクトリでない場合はスキップ
                if (!is_dir($directoryPath)) continue;
                // ディレクトリ名を配列に追加
                array_push($dirs, $directory);
            }
            return $dirs;

        } catch(Exception $e) {
            Log::error($e);
            return [];
        }
    }

    /**
     * 指定されたディレクトリ内のファイル一覧を取得します。
     *
     * @param string $path ディレクトリのパス
     * @param array $includeExtensions ファイルの拡張子の配列
     * @return array ファイル名またはファイル名（拡張子なし）の配列
     */
    public static function getIncludedExtensionFiles(string $path, array $includeExtensions = []): array
    {
        if (!is_dir($path)) return [];

        $directories =  array_diff(scandir($path), ['.', '..']);
        $files = [];
        foreach ($directories as $directory) {
            $directoryPath = $path . DIRECTORY_SEPARATOR . $directory;
            // ディレクトリの場合はスキップ
            if (is_dir($directoryPath)) continue;

            if (0 === count($includeExtensions)) {
                // 拡張子のフィルタが設定されていない場合、ファイル名を配列に追加
                array_push($files, $directory);
                continue;
            }

            $fileExtension = pathinfo($directory, PATHINFO_EXTENSION);
            $fileNameWithExtension = pathinfo($directory, PATHINFO_FILENAME);

            if (!in_array($fileExtension, $includeExtensions)) continue;
            // ファイル名（拡張子なし）を配列に追加
            array_push($files, pathinfo($directory));
        }
        return $files;
    }

    public static function searchFiles(string $dirPath, string $filename): array {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath));

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $pathInfo = pathinfo($file);
                if($pathInfo['basename'] == $filename) {
                    return $pathInfo;
                }
            }
        }

        return [];
    }

    public function searchForContainedDirectories(array $dirs)
    {
    }

    public function containDirectory(string $directoryName)
    {
    }
}
