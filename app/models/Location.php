<?php

namespace App\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Location extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'country_id', 'is_active'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }

    public static function activeLocations($country_id=0, $id = 0)
    {
        $query = self::query()->where('is_active', 'Y')->where('country_id', $country_id);
        if( $id > 0)
            $query->orWhere('id', $id);
        
        return $query->pluck('name', 'id')->prepend('None', 0);
    }
}
