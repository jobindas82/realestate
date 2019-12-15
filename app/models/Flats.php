<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Essentials\UriEncode;

class Flats extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'flats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'building_id', 'floor', 'premise_id', 'square_feet', 'minimum_value', 
        'construction_type_id', 'flat_type_id', 'plot_no', 'owner_name', 'landlord_name', 'is_available'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }

    public function encoded_key()
    {
        return $this->exists ? UriEncode::encrypt((int) $this->id) : '';
    }

    public function building()
    {
        return $this->belongsTo(Buildings::class, 'building_id');
    }

    public function construction()
    {
        return $this->belongsTo(ConstructionTypes::class, 'construction_type_id');
    }

    public function flat_type()
    {
        return $this->belongsTo(FlatTypes::class, 'flat_type_id');
    }

    public function occupancy(){
        if( $this->is_available == 1 )
            return 'Available';
        else if( $this->is_available == 2 )
            return 'Occupied';
        else
            return 'Blocked';
    }
}
