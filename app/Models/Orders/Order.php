<?php

namespace App\Models\Orders;

use App\Models\Range\Item;
use App\Models\Traits\Filterable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use Filterable;

    public $timestamps = false;
    protected $table = 'orders';
    protected $guarded = false;

    private static array $columnsAndSortNameMatching = [
        'creation_date' => 'Дата создания',
        'closing_date' => 'Дата закрытия',
        'launch_date' => 'Дата запуска',
        'completion_date' => 'Дата завершения'
    ];

    private static array $sortDirectionAndSortNameMatching = [
        'ASC' => 'Сначала старые',
        'DESC' => 'Сначала новые'
    ];

    public function status()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'order_item')
            ->withPivot('per_unit_price', 'cnt', 'amount', 'cost')
            ->using(OrderItem::class);
    }

    public function putIntoProduction()
    {
        $this->update([
            'status_id' => OrderStatus::IN_PRODUCTION,
            'launch_date' => now()
        ]);
    }

    public function completeProduction()
    {
        $this->update(['status_id' => OrderStatus::PRODUCTION_COMPLETED]);
    }

    public function sendOnShipment()
    {
        $this->update(['status_id' => OrderStatus::ON_SHIPMENT]);
    }

    public function ship()
    {
        $this->update([
            'status_id' => OrderStatus::SHIPPED,
            'completion_date' => now()
        ]);
    }

    public static function getColumnsAndSortNameMatching(): array
    {
        return self::$columnsAndSortNameMatching;
    }

    public static function getSortDirectionAndSortNameMatching(): array
    {
        return self::$sortDirectionAndSortNameMatching;
    }

    public function isPending()
    {
        return $this->status_id == OrderStatus::PENDING;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->status_id = OrderStatus::PENDING;
            $order->creation_date =
                Carbon::createFromFormat('d.m.Y', $order->creation_date);
        });
    }

    protected function creationDate(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => Carbon::parse($value)->format('d.m.Y'),
        );
    }

    protected function launchDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('d.m.Y') : '',
        );
    }

    protected function closingDate(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d.m.Y'),
        );
    }

    protected function completionDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('d.m.Y') : '',
        );
    }

    protected function customerInn(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Customer::getCustomerInnByCustomerName($value),
        );
    }
}
