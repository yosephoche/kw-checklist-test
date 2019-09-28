<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description', 'is_completed', 'completed_at', 'due', 
        'urgency', 'created_by', 'updated_by', 'created_at', 'updated_at', 
        'assignee_id', 'task_id', 'checklist_id', 'user_id'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'due' => 'datetime',
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

        static::saving(function ($model) {
            if ($model->is_completed == true) {
                $model->completed_at = Carbon::now();
            }
        });

        static::saved(function ($model) {
            
        });
    }

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function histories()
    {
        return $this->morphToMany(App\Tag::class, 'loggable');
    }
}
