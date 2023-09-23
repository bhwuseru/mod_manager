<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryModMetafile extends Model
{
    use HasFactory;

    protected $table = 'category_mod_metafile';

    protected $fillable = [
        'directory_name',
        'category_id',
        'mod_metafile_id',
        'sortno',
    ];
}
