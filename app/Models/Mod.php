<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'directory_name',
        'contain_client_tools',
        'is_separator',
    ];
}
