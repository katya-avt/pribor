<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'cover_item';
    protected $guarded = false;
}
