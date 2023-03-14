<?php

namespace App\Traits;

use Illuminate\Support\Str;

Trait Slugable{
    function createSlug(string $name):string{

        $slug = Str::slug($name);
        $i = static::where('slug', 'like', "$slug%")->count();
        if ($i > 0) {
            $slug .= "-".($i+1);
        }

        return $slug;
    }

}
