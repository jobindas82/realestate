<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Essentials\UriEncode;

class Buildings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buildings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'ownership', 'owner_name', 'landlord_name', 'purchase_date',
        'depreciation_percentage', 'floor_count', 'address', 'country_id', 'location_id',
        'is_available'
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

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function encoded_key(){
        return UriEncode::encrypt((int) $this->id);
    }

    public function formated_purchase_date(){
        return $this->exists  ? date('d/m/Y', strtotime($this->purchase_date)) : '';
    }
}
