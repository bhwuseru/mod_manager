<?php

namespace App\Domains;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryDomain
{
    public function postCsvData($csvFilePath)
    {
        try {
            // CSVファイルのパス
            // CSVファイルを読み込み
            $csvData = array_map('str_getcsv', file($csvFilePath));
            // ヘッダー行を取得
            $headers = array_shift($csvData);
            foreach ($csvData as $row) {
                $category_id = $row[0];
                $category = Category::where('category_id', $category_id)->get();
                if ($category->isNotEmpty()) {
                    $category->delete();
                }
                // データを整形・変換する場合、ここで処理を行う
                // バリデーションが必要な場合、ここでバリデーションを行う
                // データベースに保存
                Category::create(array_combine($headers, $row));
            }
        } catch (Exception $e) {
            Log::error($e);
            throw new Exception($e);
        }
    }
}
