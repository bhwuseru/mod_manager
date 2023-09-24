<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plugin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'directory_name',
        'plugin_name',
    ];
}
