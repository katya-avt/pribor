<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $guarded = false;

    const M = 'м';
    const L = 'л';
    const KG = 'кг';
    const T = 'т';
    const U = 'шт';

    const METER = 'Метр';
    const LITER = 'Литр';
    const KILOGRAM = 'Килограмм';
    const TON = 'Тонна';
    const UNIT = 'Штука';

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public static function getUnitCodeByUnitShortName(string $unitShortName): string
    {
        return self::query()->select('code')->where('short_name', $unitShortName)->first()['code'];
    }

    public function isUnit(): bool
    {
        return $this->name === self::UNIT;
    }
}
