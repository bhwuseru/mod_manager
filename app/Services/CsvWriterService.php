<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class CsvWriterService
{
    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function appendToCsv($data, $header = '')
    {
        // CSVファイルの存在を確認
        if (!Storage::disk('local')->exists($this->filePath)) {
            // ファイルが存在しない場合、新しいCSVファイルを作成
            if (!empty($header)) {
                Storage::disk('local')->put($this->filePath, $header . "\n");
            }
        }

        // CSVファイルにデータを追記
        $csvData = implode(',', $data) . "\n";
        Storage::disk('local')->append($this->filePath, $csvData);
    }
}
