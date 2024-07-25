<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedItem extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'purchased_items';
    protected $primaryKey = 'item_id';
    protected $keyType = 'int';
    protected $guarded = false;

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function purchasePriceChanges()
    {
        return $this->hasMany(PurchasedItemPurchasePriceHistory::class, 'item_id');
    }

    protected function unitCode(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Unit::getUnitCodeByUnitShortName($value),
        );
    }

    protected function purchaseLot(): Attribute
    {
        //возвращает только целую часть числа, если в дробной исключительно нули
        //т.к. в базе это значение имеет фиксированное число знаков после запятой
        //без этого при редактировании будет ошибка: "Значение для штучных изделий должно быть целым"
        return Attribute::make(
            get: fn (string $value) => fmod($value, 1) == 0 ? intval($value) : $value,
        );
    }

    protected function orderPoint(): Attribute
    {
        //возвращает только целую часть числа, если в дробной исключительно нули
        //т.к. в базе это значение имеет фиксированное число знаков после запятой
        //без этого при редактировании будет ошибка: "Значение для штучных изделий должно быть целым"
        return Attribute::make(
            get: fn (string $value) => fmod($value, 1) == 0 ? intval($value) : $value,
        );
    }
}
