<?php

namespace App\Models;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagePost extends Model
{
    use HasFactory, Slugable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'url',
        'slug',
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'url' => 'string',
        'slug' => 'string',
    ];

     /**
     * Set the slug attribute when adding the title.
     *
     * @param string $value
     * @return void
     */
    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = $value;
        // Generate a unique slug
        $this->attributes['slug'] = $this->createUniqueSlug($value);
    }


}
