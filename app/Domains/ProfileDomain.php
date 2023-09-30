<?php

namespace App\Domains;

use Exception;

class ProfileDomain {

    public $profileName;
    public $pluginList;
    public function __construct(string $dirPath)
    {
        if(!is_dir($dirPath)) throw new Exception("ProfileDomain constaract error: dirPath is not a directory {$dirPath}");
        $this->profileName = basename($dirPath);

        $pluginTextPath = $dirPath . DIRECTORY_SEPARATOR . 'plugins.txt';
        if(!is_file($pluginTextPath)) {
            throw new Exception("ProfileDomain constaract error: plugins.text is not a file {$pluginTextPath}");
        }

    }
}
