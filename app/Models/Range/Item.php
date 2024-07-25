<?php

namespace App\Models\Range;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory;
    use Filterable;
    use SoftDeletes;

    public $timestamps = false;
    protected $table = 'items';
    protected $guarded = false;

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function mainWarehouse()
    {
        return $this->belongsTo(MainWarehouse::class);
    }

    public function manufactureType()
    {
        return $this->belongsTo(ManufactureType::class);
    }

    public function detail()
    {
        return $this->hasOne(Detail::class, 'item_id');
    }

    public function purchasedItem()
    {
        return $this->hasOne(PurchasedItem::class, 'item_id');
    }

    public function currentSpecification()
    {
        return $this->belongsTo(Specification::class, 'specification_number');
    }

    public function currentCover()
    {
        return $this->belongsTo(Cover::class, 'cover_number');
    }

    public function currentRoute()
    {
        return $this->belongsTo(Route::class, 'route_number');
    }

    public function specifications()
    {
        return $this->belongsToMany(Specification::class, 'item_specification');
    }

    public function relatedSpecifications()
    {
        return $this->belongsToMany(Specification::class, 'specification_item');
    }

    public function covers()
    {
        return $this->belongsToMany(Cover::class, 'item_cover');
    }

    public function relatedCovers()
    {
        return $this->belongsToMany(Cover::class, 'cover_item');
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'item_route');
    }

    public static function getItemByDrawing(string $drawing)
    {
        return Item::query()->where('drawing', '=', $drawing)->first();
    }

    protected function itemTypeId(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ItemType::getItemTypeIdByItemTypeName($value),
        );
    }

    protected function groupId(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Group::getGroupIdByGroupName($value),
        );
    }

    protected function unitCode(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Unit::getUnitCodeByUnitShortName($value),
        );
    }

    protected function mainWarehouseCode(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => MainWarehouse::getMainWarehouseCodeByMainWarehouseName($value),
        );
    }

    protected function manufactureTypeId(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ManufactureType::getManufactureTypeIdByManufactureTypeName($value),
        );
    }

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
