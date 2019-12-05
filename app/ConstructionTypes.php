<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ConstructionTypes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'construction_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active', 'tax_code'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }

    public function taxcode()
    {
        return $this->belongsTo(TaxCode::class, 'tax_code');
    }
}
