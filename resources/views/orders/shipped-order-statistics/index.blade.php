@extends('layouts.main')
@section('content')

    @include('includes.filters.shipped-order-statistics')

    <x-detailed-information.key-value
        key="Общая прибыль:"
        value="{{ round($totalProfitValue, 2) }}">
    </x-detailed-information.key-value>

    <x-detailed-information.key-value
        key="Средняя рентабельность:"
        value="{{ round($averageProfitabilityValue, 2) }}">
    </x-detailed-information.key-value>

    <x-detailed-information.key-value
        key="Кол-во заказов, отгруженных с нарушением срока:"
        value="{{ $ordersShippedOutOfTimeCount }}">
    </x-detailed-information.key-value>

    @foreach($customerGroups->zip($valueGroups) as $customersValues)
        <canvas class="customer-distribution-chart-canvas" height="200" style="height: 300px;"
                data-labels="{{ json_encode($customersValues[0]->values()) }}"
                data-values="{{ json_encode($customersValues[1]->values()) }}"></canvas>
    @endforeach
@endsection
