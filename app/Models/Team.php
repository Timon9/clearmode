<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;



class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'public',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'public' => 'boolean',
    ];

    /**
     * The users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Set the slug attribute when adding the name.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute(string $value)
    {
        $this->attributes['name'] = $value;
        // Generate a unique slug
        $slug = Str::slug($value);
        $i = static::where('slug', 'like', "$slug%")->count();
        if ($i > 0) {
            $slug .= "-".($i+1);
        }
        $this->attributes['slug'] = $slug;
    }
}
