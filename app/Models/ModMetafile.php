<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModMetafile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table  = 'mod_metafiles';

    protected $fillable = [
        'directory_name',
        'game_name',
        'modid',
        'version',
        'newest_version',
        'nexus_file_status',
        'installation_file',
        'repository',
        'nexus_file_status',
        'ignoredVersion',
        'fileid',
        'has_custom_url',
        'comments',
        'color',
        'nexus_description',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->withPivot('sortno') // ソート順を保持するためのカラム
            ->orderBy('category_mod_metafile.sortno'); // ソート順で並び替え
    }
}
