<?php

namespace App\Models;


class ImagePost extends Post
{

    /**
     * The attributes that are mass assignable (extending the parent Post values).
     *
     * @var array<int, string>
     */
    protected $extend_fillable = [
        'url',
    ];

     /**
     * The attributes that should be cast (extending the parent Post values).
     *
     * @var array
     */
    protected $extend_casts = [
        'url' => 'string',
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge($this->fillable,$this->extend_fillable);
        $this->casts = array_merge($this->casts,$this->extend_casts);
    }
}
