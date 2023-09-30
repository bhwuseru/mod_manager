<?php

namespace App\Services;

class TextManipulationService
{
    public $textArray = [];
    private $path;

    public function __construct(string $path)
    {
        // テキストファイルを読み込み、各行を配列に格納
        $this->textArray = file($path, FILE_IGNORE_NEW_LINES);
        $this->path = $path;
    }

    // 指定した文字列で始まる行を抽出するメソッド
    public function extractLinesStartingWith(string $startsWith): TextManipulationService
    {
        $extractedLines = [];
        $cloneTextMain = new TextManipulationService($this->path);

        foreach ($cloneTextMain->textArray as $line) {
            if (strpos(trim($line), $startsWith) === 0) {
                $extractedLines[] = $line;
            }
        }

        $cloneTextMain->textArray = $extractedLines;
        return $cloneTextMain;
    }

    // 指定した文字列で始まる行を除外するメソッド
    public function removeLinesStartingWith(string $startsWith): TextManipulationService
    {
        $cloneTextMain = new TextManipulationService($this->path);
        $cloneTextMain->textArray = array_filter($cloneTextMain->textArray, function ($line) use ($startsWith) {
            return strpos(trim($line), $startsWith) !== 0;
        });

        return $cloneTextMain;
    }

    // 現在のテキストを取得するメソッド
    public function getTextArray(): array
    {
        return $this->textArray;
    }
}

// サービスクラスの使用例
$textManipulationService = new TextManipulationService('path/to/your/textfile.txt');

// 特定の文字列で始まる行を抽出
$extractedLines = $textManipulationService->extractLinesStartingWith('*');
print_r($extractedLines);

// 特定の文字列で始まる行を除外
$textManipulationService->removeLinesStartingWith('#');

// 現在のテキストを取得
$currentTextArray = $textManipulationService->getTextArray();
print_r($currentTextArray);
