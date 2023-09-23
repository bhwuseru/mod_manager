<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetaCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ='meta_categories';

    protected $fillable = [
        'mod_metafile_id',
        'category_id',
        'sorted_no',
    ];
}
