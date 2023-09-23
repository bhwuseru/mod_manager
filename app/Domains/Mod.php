<?php

namespace App\Domains;

class Mod
{

    public array $pluginFiles = [];
    public string $directoryName = '';
    public Metafile $metafile;
    public string $calienteTools = '';
    public function __construct()
    {
    }
}
