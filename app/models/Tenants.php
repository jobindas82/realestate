<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Essentials\UriEncode;

class Tenants extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tenants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'emirates_id', 'land_phone', 'mobile', 'email', 'passport_number', 'trn_number', 'is_available'
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

    public function status()
    {
        if ($this->is_available == 1)
            return 'Active';
        else if ($this->is_available == 2)
            return 'On Contract';
        else
            return 'Blocked';
    }

    public function onContract()
    {
        $this->is_available = 2;
        $this->save();
    }

    public function makeAvailable()
    {
        $this->is_available = 1;
        $this->save();
    }
}
