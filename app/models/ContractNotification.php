<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Essentials\UriEncode;

class ContractNotification extends Model
{
    protected $table = 'contract_notification';

    public function encoded_key()
    {
        return UriEncode::encrypt((int) $this->contract_id);
    }

    public function formated_to_date()
    {
        return date('d/m/Y', strtotime($this->end_date));
    }
}
