<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainWarehouse extends Model
{
    use HasFactory;

    protected $table = 'main_warehouses';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $guarded = false;

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public static function getMainWarehouseCodeByMainWarehouseName(string $mainWarehouseName): string
    {
        return self::query()->select('code')->where('name', $mainWarehouseName)->first()['code'];
    }
}
