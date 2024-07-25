<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected function cnt(): Attribute
    {
        //возвращает только целую часть числа, если в дробной исключительно нули
        //т.к. в базе это значение имеет фиксированное число знаков после запятой
        //без этого при редактировании будет ошибка: "Значение для штучных изделий должно быть целым"
        return Attribute::make(
            get: fn (string $value) => fmod($value, 1) == 0 ? intval($value) : $value,
        );
    }
}
