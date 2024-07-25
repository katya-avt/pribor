<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'details';
    protected $primaryKey = 'item_id';
    protected $keyType = 'int';
    protected $guarded = false;

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
