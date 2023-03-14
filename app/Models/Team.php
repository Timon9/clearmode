<?php

namespace App\Models;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Team extends Model
{
    use HasFactory, Slugable;

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
        $this->attributes['slug'] = $this->createSlug($value);
    }
}
