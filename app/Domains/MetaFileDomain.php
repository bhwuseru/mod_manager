<?php

namespace App\Domains;

use App\Models\CategoryModMetafile;
use App\Models\ModMetafile;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Continue_;

class MetaFileDomain
{
    // 親ディレクトリの名前
    public string $parentDirName;

    // ゲーム名
    public string $gameName;

    // Mod ID
    public int $modid;

    // バージョン
    public string $version = "";

    // 最新バージョン
    public string $newestVersion = "";

    // カテゴリ
    public array $category = [];

    // Nexus ファイルのステータス
    public string $nexusFileStatus = "";

    // インストールファイル
    public string $installationFile = "";

    // リポジトリ
    public string $repository = "";

    // 無視されるバージョン
    public string $ignoredVersion = "";

    // コメント
    public string $comments = "";

    // ノート
    public string $notes = "";

    // Nexus の説明
    public string $nexusDescription = "";

    // URL
    public string $url = "";

    // カスタムURLがあるかどうかのフラグ
    public bool $hasCustomUrl = false;

    // 最後の Nexus クエリ日時
    public CarbonInterface $lastNexusQuery;

    // 最後の Nexus 更新日時
    public CarbonInterface $lastNexusUpdate;

    // Nexus の最終変更日時
    public CarbonInterface $nexusLastModified;

    // 変換済みかどうかのフラグ
    public bool $converted = false;

    // 検証済みかどうかのフラグ
    public bool $validated = false;

    // カラー
    public string $color = "";

    // トラッキング数
    public int $tracked;

    // 賛同数
    public int $endorsed;

    // ファイルID
    public int $fileid;

    // ファイルサイズ
    public int $size;

    // ModMetafile インスタンスまたは null
    public ModMetafile | null $modMetafile = null;

    // CategoryModMetafile インスタンスの配列
    public $categoryModMetafiles = [];

    /**
     * MetaFileDomain コンストラクタ
     *
     * @param string $dirPath ディレクトリのパス
     */
    public function __construct(string $dirPath, string $downloadPath = '')
    {
        if (!is_dir($dirPath)) {
            throw new Exception("MetaFileDomain: 引数dirPath:  {$dirPath}がディレクトリではありません");
        }

        // 親ディレクトリ名を取得
        $this->parentDirName = basename($dirPath);

        // meta.ini ファイルを解析してデータを設定
        $success = $this->createMetaFile($dirPath);

        // MetaFileDomain が作成できなかった場合、$modMetafile を null に設定
        if (!$success) {
            $this->modMetafile = null;
        }
    }

    /**
     * meta.ini ファイルを解析してデータを設定
     *
     * @param string $dirPath ディレクトリのパス
     * @return bool 成功時に true、失敗時に false
     */
    private function createMetaFile(string $dirPath): bool
    {
        $metafilePath = $dirPath . DIRECTORY_SEPARATOR . 'meta.ini';

        // meta.ini ファイルが存在しない場合は処理終了
        if (!file_exists($metafilePath)) {
            return false;
        }

        try {
            $metaIniContent = file_get_contents($metafilePath);
            $lines = explode("\n", $metaIniContent);
            $this->modMetafile = new ModMetafile;
            $this->modMetafile->directory_name = $this->parentDirName;

            foreach ($lines as $line) {
                // 行を解析してキーと値を取得
                $parts = explode('=', $line, 2);

                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);

                    if ($key === 'category') {
                        $categories = explode(',', $value);
                        $this->$key = $categories;
                        $sortno = 0;

                        // CategoryModMetafile の配列を生成
                        foreach ($categories as $categoryId) {
                            $sortno++;
                            $categoryModMetafie = new CategoryModMetafile;
                            $categoryModMetafie->directory_name = $this->parentDirName;
                            $categoryModMetafie->sortno = $sortno;
                            $categoryModMetafie->category_id = intval($categoryId);
                            $this->categoryModMetafiles = [
                                ...$this->categoryModMetafiles,
                                $categoryModMetafie,
                            ];
                        }
                        continue;
                    }
                    if($key === 'hasCustomURL') {
                        $this->hasCustomUrl = $value;
                        continue;
                    }

                    if($key === 'lastNexusQuery') {
                        $key = Str::snake($key);
                        $dtttime = Carbon::parse($value);
                        if($dtttime->isValid()) {
                            $this->$key = $dtttime->toDateTimeString();
                        }
                        continue;
                    }
                    if($key === 'lastNexusUpdate') {
                        $key = Str::snake($key);
                        $dtttime = Carbon::parse($value);
                        if($dtttime->isValid()) {
                            $this->$key = $dtttime->toDateTimeString();
                        }
                        continue;
                    }
                    if($key === 'nexusLastModified') {
                        $key = Str::snake($key);
                        $dtttime = Carbon::parse($value);
                        if($dtttime->isValid()) {
                            $this->$key = $dtttime->toDateTimeString();
                        }
                        continue;
                    }
                    if (strpos($key, "%") !== false) {
                        continue;
                    }
                    if($key === 'installationFile') {
                        echo $key . ': ' . $value . PHP_EOL;
                    }
                    $key = preg_replace('/^1\\\/', '', $key);

                    // カラム名をローワーキャメルケースからスネークケースに変換
                    $key = Str::snake($key);
                    $this->$key = $value;
                    $this->modMetafile->$key = $value;
                }
            }

            return true;
        } catch (Exception $e) {
            // エラーが発生した場合、ログに記録
            Log::error($e);
            return false;
        }
    }

    /**
     * メタファイルとカテゴリ関連のデータをデータベースに登録
     */
    public function register()
    {
        if (is_null($this->modMetafile)) {
            return;
        }
        // dd($this->modMetafile);
        try{
            $this->modMetafile->save();
        }catch(Exception $e) {
            // dd( $this->modMetafile);
            dd($e);
        }

        foreach ($this->categoryModMetafiles as $file) {
            // CategoryModMetafile を保存
            $file->mod_metafile_id = $this->modMetafile->id;
            try {
                $file->save();
            } catch(Exception $e) {
                dd($e);
            }
        }

    }

    /**
     * メタファイルを更新
     */
    public function update()
    {
        // TODO: メタファイルの更新ロジックを追加
    }
}
