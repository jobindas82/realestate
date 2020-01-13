<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Essentials\UriEncode;

class VoucherNotification extends Model
{
    protected $table = 'voucher_notification';

    public function encoded_key(){
        return UriEncode::encrypt((int) $this->id);
    }
}
