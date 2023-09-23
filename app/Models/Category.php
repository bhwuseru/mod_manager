<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function modMetafiles()
    {
        return $this->belongsToMany(ModMetafile::class)
            ->withPivot('sortno') // ソート順を保持するためのカラム
            ->orderBy('category_mod_metafile.sortno'); // ソート順で並び替え
    }
}
