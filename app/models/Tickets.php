<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Tickets extends Model
{
    const PRIORITIES = [1 => 'Low', 2 => 'Medium', 4 => 'High', 5 => 'Critical'];
    const STATUS = [0 => 'Closed', 1 => 'Active', 2 => 'In Progress'];

    protected $table = 'ticket';

    protected $fillable = [
        'date', 'tenant_id', 'contract_id', 'job_type', 'details', 'is_active', 'priority', 'remarks', 'amount'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }

    public function contract()
    {
        return $this->belongsTo(Contracts::class, 'contract_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenants::class, 'tenant_id');
    }

    public function convertToJob()
    {
        $this->job_type = 2;
        $this->save();
    }

    public function formated_date()
    {
        return $this->exists && $this->date != NULL &&   $this->date != '0000-00-00' ? date('d/m/Y', strtotime($this->date)) : '';
    }

    public function whichPriority()
    {
        return self::PRIORITIES[$this->priority];
    }

    public function ticketStatus()
    {
        return self::STATUS[$this->is_active];
    }

    public static function ticketsThisWeekCount()
    {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        return self::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    }

    public static function activeTicketsCount()
    {
        return self::where('is_active', 1)->count();
    }

    public static function criticalTicketsCount()
    {
        return self::where('is_active', 1)->where('priority', 5)->count();
    }

    public static function runningJobsCount()
    {
        return self::where('is_active', 1)->where('job_type', 2)->count();
    }
}
