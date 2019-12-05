<?php

namespace App;

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

    public static function activeTypes($id = 0)
    {
        $query = self::query()->where('is_active', 'Y');
        if ($id > 0)
            $query->orWhere('id', $id);
        $model = $query->get();
        $response = [];
        foreach ($model as $each) {
            $response[$each->id] = $each->name;
        }
        return $response;
    }
}
