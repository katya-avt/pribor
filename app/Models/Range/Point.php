<?php

namespace App\Models\Range;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    use Filterable;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'points';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $guarded = false;

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_point')
            ->withPivot('point_number', 'operation_code', 'rate_code', 'unit_time', 'working_time', 'lead_time');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function basePaymentChanges()
    {
        return $this->hasMany(PointBasePaymentHistory::class, 'point_code');
    }
}
