<?php

namespace App\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ledgers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ledgers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'is_active',
    ];

    public static function children($id = 0)
    {
        $query = self::query()->where('is_active', 'Y')->where('is_parent', 'N');
        if ($id > 0)
            $query->orWhere('id', $id);
        return $query->pluck('name', 'id');
    }
}
