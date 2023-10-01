<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Download extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'installationFile',
        'mod_id',
        'name',
        'modName',
        'gameName',
        'modID',
        'fileID',
        'url',
        'description',
        'version',
        'newestVersion',
        'fileTime',
        'fileCategory',
        'category',
        'repository',
        'installed',
        'uninstalled',
        'paused',
        'removed',
        'metaFilePath',
        'downloadPath',
    ];
}
