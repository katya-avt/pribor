<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = 'operations';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $guarded = false;

    public function operations()
    {
        return $this->hasMany(Operation::class, 'operation_code');
    }

    public function descendants()
    {
        return $this->hasMany(Operation::class, 'operation_code')->with('operations');
    }

    public function ancestor()
    {
        return $this->belongsTo(Operation::class, 'operation_code');
    }

    public function ancestors()
    {
        return $this->ancestor()->with('ancestors');
    }
}
