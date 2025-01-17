<?php

namespace App\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JobTypes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_types';

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

    public static function activeTypes($id = 0)
    {
        $query = self::query()->where('is_active', 'Y');
        if( $id > 0)
            $query->orWhere('id', $id);
        
        return $query->pluck('name', 'id')->prepend('None', 0);
    }
}
