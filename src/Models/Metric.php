<?php

namespace AlifAhmmed\MetricsTracker\Models;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    protected $fillable = [
        'trackable_type',
        'trackable_id',
        'type',
        'user_id',
        'url',
        'device_ip',
    ];

    public function trackable()
    {
        return $this->morphTo();
    }
}
