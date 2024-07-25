<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;

    protected $table = 'item_types';
    protected $guarded = false;

    const PROPRIETARY = 'Собственный';
    const PURCHASED = 'Покупной';
    const TOLLING = 'Давальческий';

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public static function getItemTypeIdByItemTypeName(string $itemTypeName): string
    {
        return self::query()->select('id')->where('name', $itemTypeName)->first()['id'];
    }

    public function isProprietary()
    {
        return $this->name == self::PROPRIETARY;
    }

    public function isPurchased()
    {
        return $this->name == self::PURCHASED;
    }

    public function isTolling()
    {
        return $this->name == self::TOLLING;
    }
}
