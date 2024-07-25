<?php

namespace App\Models\Range;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    use Filterable;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'routes';
    protected $primaryKey = 'number';
    protected $keyType = 'string';
    protected $guarded = false;

    public function points()
    {
        return $this->belongsToMany(Point::class, 'route_point')
            ->withPivot('point_number', 'operation_code', 'rate_code', 'unit_time', 'working_time', 'lead_time');
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class, 'route_point')
            ->withPivot('point_number', 'point_code', 'rate_code', 'unit_time', 'working_time', 'lead_time');
    }

    public function rates()
    {
        return $this->belongsToMany(Rate::class, 'route_point')
            ->withPivot('point_number', 'point_code', 'operation_code', 'unit_time', 'working_time', 'lead_time');
    }

    public function relatedItems()
    {
        return $this->belongsToMany(Item::class, 'item_route');
    }
}
