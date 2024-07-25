<?php

namespace App\Models\Range;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cover extends Model
{
    use HasFactory;
    use Filterable;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'covers';
    protected $primaryKey = 'number';
    protected $keyType = 'string';
    protected $guarded = false;

    public function items()
    {
        return $this->belongsToMany(Item::class, 'cover_item')
            ->withPivot('area', 'consumption');
    }

    public function relatedItems()
    {
        return $this->belongsToMany(Item::class, 'item_cover');
    }

    public static function getConsumptionAttributeName()
    {
        return 'consumption';
    }

    public static function getNamesOfAdditionalAttributesRequireSummation()
    {
        return ['area'];
    }

    public static function getNamesOfAdditionalAttributesNotRequireSummation()
    {
        return [];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('valid', function (Builder $builder) {
            $builder->whereNull('valid_to');
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cover) {
            $cover->valid_from = $cover->valid_from ?: now();
        });
    }
}
