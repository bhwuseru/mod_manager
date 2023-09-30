<?php

namespace App\Domains;

use App\Models\Category;
use App\Models\CategoryModMetafile;
use App\Models\Mod;
use App\Models\Plugin;
use Illuminate\Database\Eloquent\Collection;

class ModViewDomain
{

    public Collection $categories;
    public $metaFile;
    public Collection $plugins;
    public Collection | null $mod;
    public $directoryName = '';
    public $isSeparator = false;
    public $containClientTools = false;

    public function __construct($directoryName)
    {
        $mod = Mod::where('directory_name', $directoryName)->first();
        if(is_null($mod)) return null;

        $this->directoryName = $mod->directory_name;
        $this->isSeparator = $mod->is_separator;
        $this->containClientTools = $mod->contain_client_tools;

        $categories = CategoryModMetafile::where('directory_name', $directoryName)
            ->get();
        $categoryIds = [];
        if ($categories->isNotEmpty()) {
            foreach ($categories as $category) {
                $categoryIds = [
                    ...$categoryIds,
                    $category->category_id,
                ];
            }
        }

        $this->categories = Category::whereIn('category_id', $categoryIds)->get();
        $this->metaFile = CategoryModMetafile::where('directory_name', $directoryName)->first();
        $this->plugins = Plugin::where('directory_name', $directoryName)->get();
    }
}
