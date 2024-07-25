@extends('layouts.main')
@section('content')

    <x-detailed-information.key-value
        key="Чертеж:"
        value="{{ $itemWithPurchaseData->drawing }}">
    </x-detailed-information.key-value>

    <x-detailed-information.key-value
        key="Наименование:"
        value="{{ $itemWithPurchaseData->name }}">
    </x-detailed-information.key-value>

    <div class="mb-3">
        <x-detailed-information.key-value
            key="Текущее значение цены покупки:"
            value="{{ $itemWithPurchaseData->purchasedItem->purchase_price }}">
        </x-detailed-information.key-value>
    </div>

    @include('includes.filters.purchased-item-purchase-price-history', ['item' => $itemWithPurchaseData])

    <canvas id="purchase-price-change-history-chart-canvas" height="200" style="height: 300px;"
            data-labels="{{ json_encode($timePoints) }}" data-values="{{ json_encode($values) }}"></canvas>
@endsection
