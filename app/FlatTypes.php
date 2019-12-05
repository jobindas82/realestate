<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FlatTypes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'flat_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }
}
