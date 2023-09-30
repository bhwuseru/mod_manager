<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class CsvReaderService
{
    public function readCsvFile($filePath)
    {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES);

        if (empty($lines)) {
            return []; // ファイルが空の場合は空の連想配列を返すかエラーハンドリングを行うこともできます
        }

        $csvData = [];
        $header = str_getcsv($lines[0]); // 最初の行をヘッダーとして取得

        // ヘッダー以外の行を処理
        for ($i = 1; $i < count($lines); $i++) {
            $data = str_getcsv($lines[$i]);
            $row = [];

            // ヘッダーの要素数とデータの要素数が一致する場合に連想配列に格納
            if (count($header) === count($data)) {
                for ($j = 0; $j < count($header); $j++) {
                    $row[$header[$j]] = $data[$j];
                }
                $csvData[] = $row;
            }
        }

        return $csvData;
    }
}
