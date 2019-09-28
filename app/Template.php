<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Template extends Model
{
    protected $fillable = ['name'];

    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'object_id');
    }
}
