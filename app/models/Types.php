<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'entry_types';
    public $timestamps = false;

    public function generate($update = true){
        $nextNumber = $this->current > 0 ? $this->current + 1 : $this->start;
        if( $update){
            $this->current = $nextNumber;
            $this->save();
        }
        return $nextNumber;
    }
}
