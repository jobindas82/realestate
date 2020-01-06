<?php

namespace App\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Essentials\UriEncode;

class Ledgers extends Model
{

    const MAX_LEVEL = 5;
    const CASH_CHILD = 'CASH_C';
    const BANK_CHILD = 'BANK_C';
    const SALES_VAT = 'VAT_S';
    const UNBALANCED_AMT = 'UBL';
    const SECURITY_DEPOSIT = 'SE_D';
    const RENT = 'RNT';
    
    protected $table = 'ledgers';

    protected $fillable = [
        'parent_id', 'name', 'is_active', 'is_contract_item'
    ];

    public $availableClasses = [
        'BANK_P' =>  self::BANK_CHILD,
        'CASH_P' => self::CASH_CHILD,
        'VAT_P' => 'VAT_C'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }
    public function group()
    {
        return $this->belongsTo(Ledgers::class, 'parent_id');
    }

    public static function parents($dontShowId = 0, $prepend = false)
    {
        $query = self::query()->where('is_active', 'Y')->where('is_parent', 'Y');
        if ($dontShowId > 0)
            $query->where('id', '!=', $dontShowId);
        $response = $query->pluck('name', 'id');
        if ($prepend)
            $response = $response->prepend('<primary>', 0);
        return $response;
    }

    public static function allChildren()
    {
        return self::query()->where('is_parent', 'N')->pluck('name', 'id');
    }

    public static function children($id = 0)
    {
        $query = self::query()->where('is_active', 'Y')->where('is_parent', 'N');
        if ($id > 0)
            $query->orWhere('id', $id);
        return $query->pluck('name', 'id');
    }

    public function encoded_key()
    {
        return $this->exists ? UriEncode::encrypt((int) $this->id) : '';
    }

    public function rootNames()
    {
       $root = '/';
       if( $this->root != NULL){
           foreach(explode(',', $this->root) as $i => $each ){
               if( $i > 0 )
                $root .='/';
               $root .= self::find($each)->name;
           }
       }
       return $root;
    }

    public function addLevel()
    {
        $level = 1;
        if ($this->parent_id > 0) {
            $level = $this->group->level + 1;
        }
        $this->level = $level;
    }

    public function inheritParent()
    {
        if ($this->parent_id > 0) {
            $this->type = $this->group->type;
        }
    }

    public function addClass()
    {
        if ($this->parent_id > 0) {
            $parentClass = $this->group->class;
            if ($parentClass != NULL && array_key_exists($parentClass, $this->availableClasses)) {
                $this->class = $this->availableClasses[$parentClass];
            }
        }
    }

    public function addRoot()
    {
        $root = [];
        if ($this->parent_id > 0) {
            $parentRoot = $this->group->root;
            if ($parentRoot != NULL)
                $root[] = $parentRoot;
            $root[] = $this->parent_id;
            $this->root = implode(',', $root);
        }
    }

    public function is_reached_maximum_level($lessThanOne = false){
        $maxLevel = $lessThanOne ? self::MAX_LEVEL - 1 : self::MAX_LEVEL;
        return $this->level > $maxLevel ? false : true;
    }

    public static function contractItems($id = 0)
    {
        $query = self::query()->where('is_active', 'Y')->where('is_parent', 'N')->where('is_contract_item', 'Y');
        if ($id > 0)
            $query->orWhere('id', $id);
        return $query->pluck('name', 'id');
    }

    public function currentBalance(){
        return 100;
    }

    public static function childrenHaveClass($id =0, $class = NULL){
        $query = self::query()->where('is_active', 'Y')->where('is_parent', 'N')->where('class', $class);
        if ($id > 0)
            $query->orWhere('id', $id);
        return $query->pluck('name', 'id')->prepend('None', 0);
    }

    public static function findClass($class = NULL){
        return self::where('class', $class)->first();
    }

    public static function onBaseFormat($amount=0, $ledger=0){
        $multipliers = ['A' => 1, 'E' => 1, 'L' => -1, 'I' => -1];
        if( $ledger > 0 ){
            $ledgerType = self::find($ledger)->type;
            return $amount * $multipliers[ $ledgerType ];
        }
        return 0;
    }
}
