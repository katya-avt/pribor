<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureType extends Model
{
    use HasFactory;

    protected $table = 'manufacture_types';
    protected $guarded = false;

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public static function getManufactureTypeIdByManufactureTypeName(string $manufactureTypeName): string
    {
        return self::query()->select('id')->where('name', $manufactureTypeName)->first()['id'];
    }

    public static function getManufactureTypeNameByManufactureTypeId(string $manufactureTypeId): string
    {
        return self::query()->select('name')->where('id', $manufactureTypeId)->first()['name'];
    }
}
