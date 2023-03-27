<?php

namespace App\Traits;

use Illuminate\Support\Str;

Trait Slugable{

    /**
     * Create a unique slug for param $name.
     *
     * @param string $name
     * @return string
     */
    function createUniqueSlug(string $name,string $slugField = 'slug'):string{
        $slug = Str::slug($name);
        $i = static::where($slugField, 'like', $slug."%")->count();
        if ($i > 0) {
            $slug .= "-".($i+1);
        }
        return $slug;
    }

}
