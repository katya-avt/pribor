<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutePoint extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'route_point';
    protected $guarded = false;
}
