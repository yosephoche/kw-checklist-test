<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Checklist extends Model
{
    protected $fillable = [
        'object_domain', 'object_id', 'description', 'is_completed', 
        'completed_at', 'created_by', 'updated_by', 'created_at', 'updated_at', 
        'due', 'urgency', 'task_id'
    ];

    protected $casts = [
        'due' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = Carbon::now();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
