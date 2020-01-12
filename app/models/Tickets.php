<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tickets extends Model
{
    const PRIORITIES = [1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Critical'];
    const STATUS = [0 => 'Closed', 1 => 'Active', 2 => 'In Progress'];
    const JOB_CATEGORIES = [
        1 => 'Carpentry',
        2 => 'Plumbing',
        3 => 'Electrical',
        4 => 'AC',
        5 => 'Landscaping',
        6 => 'Pest Control',
        7 => 'Garbage',
        8 => 'Cleaning',
        9 => 'Security',
        10 => 'Others'
    ];

    protected $table = 'ticket';

    protected $fillable = [
        'date', 'tenant_id', 'contract_id', 'job_type', 'details', 'is_active', 'priority', 'remarks', 'amount', 'job_category'
    ];

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

    public function revertToTicket()
    {
        $this->job_type = 1;
        $this->save();
    }

    public function markFinished()
    {
        $this->is_active = 0;
        $this->save();
    }

    public function formated_date()
    {
        return $this->exists && $this->date != NULL &&   $this->date != '0000-00-00' ? date('d/m/Y', strtotime($this->date)) : date('d/m/Y');
    }

    public function whichPriority()
    {
        return self::PRIORITIES[$this->priority];
    }

    public function whichCategory()
    {
        return self::JOB_CATEGORIES[$this->job_category];
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

    public function isTicket(){
        return $this->job_type == 1 ? true : false;
    }

    public function inProgress(){
        $this->is_active =2;
        $this->save();
    }
}
