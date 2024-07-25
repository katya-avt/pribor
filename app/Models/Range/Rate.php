<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = 'rates';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $guarded = false;
}
