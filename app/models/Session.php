<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Essentials\UriEncode;

class Session extends Model
{
    protected $table = 'sessions';
    public $timestamps = false;

    public static function set($enc = NULL){
        if( $enc != NULL){
            $model = new self;
            $model->enc = $enc;
            $model->expires = Carbon::now()->addHour();
            $model->save();
            return true;
        }
        return false;
    }

    public static function isActive($enc){
        $model = self::where('enc', $enc)->first();
        return isset($model->expires) ? Carbon::parse($model->expires)->gt(Carbon::now()) : false;
    }

    public static function clear($enc){
        self::where('enc', $enc)->delete();
    }

    public static function tenant($enc){
        $id = UriEncode::decrypt($enc);
        return \App\models\Tenants::find($id);
    }
}
