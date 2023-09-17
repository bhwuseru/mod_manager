<?php

namespace App\Services;

class DirectoryService
{

    public static function getDirs(string $path): array
    {

        $dirs = [];
        $directories =  array_diff(scandir($path), ['.', '..']);
        foreach ($directories as $directory) {

            $directoryPath = $path. DIRECTORY_SEPARATOR . $directory;
            if (!is_dir($directoryPath)) continue;
            array_push($dirs, $directory);
        }

        return $dirs;
    }

    public static function getFiles(string $path, array $includeExtentsions  = []): array
    {
        if(!is_dir($path)) return [];

        $directories =  array_diff(scandir($path), ['.', '..']);
        $files = [];
        foreach ($directories as $directory) {

            $directoryPath = $path . DIRECTORY_SEPARATOR . $directory;
            if (is_dir($directoryPath)) continue;

            if (0 === count($includeExtentsions)) {
                array_push($files, $directory);
                continue;
            }

            $fileExtension = pathinfo($directory, PATHINFO_EXTENSION);
            $fileNameWithExtension = pathinfo($directory, PATHINFO_FILENAME);

            if (!in_array($fileExtension, $includeExtentsions)) continue;
            array_push($files, $fileNameWithExtension);
        }

        return $files;
    }

}
