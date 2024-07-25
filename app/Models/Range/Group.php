<?php

namespace App\Models\Range;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $guarded = false;

    const DETAIL = 'Детали';
    const FASTENER = 'Крепеж';
    const MATERIAL = 'Материалы';
    const COVER = 'Покрытия';
    const ASSEMBLY_ITEM = 'Сборочные единицы';

    const CABLE_ITEM = 'Кабельные изделия';
    const METAL = 'Металлы';
    const PLASTIC = 'Пластмассы';
    const CHEMICAL = 'Химикаты';
    const VARIOUS = 'Разные материалы';
    const GALVANIC = 'Гальванические покрытия';
    const PAINT = 'Лакокрасочные покрытия';

    private static array $groupsAndBaseUnitsMatching = [
        self::DETAIL => Unit::U,
        self::FASTENER => Unit::U,
        self::ASSEMBLY_ITEM => Unit::U,
        self::CABLE_ITEM => Unit::M,
        self::METAL => Unit::KG,
        self::PLASTIC => Unit::KG,
        self::CHEMICAL => Unit::L,
        self::VARIOUS => Unit::U,
        self::GALVANIC => Unit::L,
        self::PAINT => Unit::L,
    ];

    private static array $groupsAndItemTypesMatching = [
        self::DETAIL => [ItemType::PROPRIETARY, ItemType::TOLLING],
        self::FASTENER => [ItemType::PURCHASED],
        self::ASSEMBLY_ITEM => [ItemType::PROPRIETARY],
        self::CABLE_ITEM => [ItemType::PURCHASED],
        self::METAL => [ItemType::PURCHASED],
        self::PLASTIC => [ItemType::PURCHASED],
        self::CHEMICAL => [ItemType::PURCHASED],
        self::VARIOUS => [ItemType::PURCHASED, ItemType::TOLLING],
        self::GALVANIC => [ItemType::PROPRIETARY, ItemType::PURCHASED],
        self::PAINT => [ItemType::PURCHASED],
    ];

    private static array $groupsAndPurchasedItemUnitsMatching = [
        self::DETAIL => Unit::U,
        self::FASTENER => Unit::U,
        self::ASSEMBLY_ITEM => Unit::U,
        self::CABLE_ITEM => Unit::M,
        self::METAL => Unit::T,
        self::PLASTIC => Unit::T,
        self::CHEMICAL => Unit::U,
        self::VARIOUS => Unit::U,
        self::GALVANIC => Unit::U,
        self::PAINT => Unit::U,
    ];

    public static function getGroupsAndBaseUnitsMatching(): array
    {
        return self::$groupsAndBaseUnitsMatching;
    }

    public static function getGroupsAndItemTypesMatching(): array
    {
        return self::$groupsAndItemTypesMatching;
    }

    public static function getGroupsAndPurchasedItemUnitsMatching(): array
    {
        return self::$groupsAndPurchasedItemUnitsMatching;
    }

    public static function getItemGroupsMeasuredInUnits(): array
    {
        $itemGroupsMeasuredInUnits = [];

        foreach (self::getGroupsAndBaseUnitsMatching() as $group => $baseUnit) {
            if ($baseUnit == Unit::U) {
                $itemGroupsMeasuredInUnits[] = $group;
            }
        }
        return $itemGroupsMeasuredInUnits;
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'group_id');
    }

    public function descendants()
    {
        return $this->hasMany(Group::class, 'group_id')->with('groups');
    }

    public function ancestor()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function ancestors()
    {
        return $this->ancestor()->with('ancestors');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public static function getGroupIdByGroupName(string $groupName): string
    {
        return self::query()->select('id')->where('name', $groupName)->first()['id'];
    }

    public function isDetail(): bool
    {
        return $this->name == self::DETAIL;
    }

    public function isFastener(): bool
    {
        return $this->name === self::FASTENER;
    }

    public function isMaterial(): bool
    {
        return $this->ancestor && $this->ancestor->name === self::MATERIAL;
    }

    public function isCover(): bool
    {
        return $this->ancestor && $this->ancestor->name === self::COVER;
    }

    public function isAssemblyItem(): bool
    {
        return $this->name === self::ASSEMBLY_ITEM;
    }

    public function isCableItem(): bool
    {
        return $this->name === self::CABLE_ITEM;
    }

    public function isMetal(): bool
    {
        return $this->name === self::METAL;
    }

    public function isPlastic(): bool
    {
        return $this->name === self::PLASTIC;
    }

    public function isChemical(): bool
    {
        return $this->name === self::CHEMICAL;
    }

    public function isVariousMaterial(): bool
    {
        return $this->name === self::VARIOUS;
    }

    public function isGalvanicCover(): bool
    {
        return $this->name === self::GALVANIC;
    }

    public function isPaintCover(): bool
    {
        return $this->name === self::PAINT;
    }
}
