<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->morphedByMany(App\Item::class, 'loggable');
    }
}
