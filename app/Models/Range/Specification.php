<?php

namespace App\Models\Range;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    use HasFactory;
    use Filterable;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'specifications';
    protected $primaryKey = 'number';
    protected $keyType = 'string';
    protected $guarded = false;

    public function items()
    {
        return $this->belongsToMany(Item::class, 'specification_item')
            ->withPivot('cnt')
            ->using(SpecificationItem::class);
    }

    public function relatedItems()
    {
        return $this->belongsToMany(Item::class, 'item_specification');
    }

    public static function getConsumptionAttributeName()
    {
        return 'cnt';
    }

    public static function getNamesOfAdditionalAttributesRequireSummation()
    {
        return [];
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

        static::creating(function ($specification) {
            $specification->valid_from = $specification->valid_from ?: now();
        });
    }
}
