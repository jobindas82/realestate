<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Essentials\UriEncode;

class Documents extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'title', 'expiry_date', 'filename', 'from', 'is_active'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }

    public function building()
    {
        return $this->belongsTo(Buildings::class, 'parent_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'parent_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'parent_id');
    }

    public function parent()
    {
        if ($this->from == 1)
            return  $this->building();
        else if( $this->from == 2 )
            return $this->contract();
        else
            return $this->tenant();
    }

    public function encoded_key()
    {
        return UriEncode::encrypt((int) $this->id);
    }

    public function formated_expiry_date()
    {
        return $this->expiry_date != NULL && $this->expiry_date != '0000-00-00' ? date('d/m/Y', strtotime($this->expiry_date)) :  NULL;
    }
}
