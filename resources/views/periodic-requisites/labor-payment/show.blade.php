@extends('layouts.main')
@section('content')

    <x-detailed-information.key-value
        key="Код:"
        value="{{ $point->code }}">
    </x-detailed-information.key-value>

    <x-detailed-information.key-value
        key="Наименование:"
        value="{{ $point->name }}">
    </x-detailed-information.key-value>

    <div class="mb-3">
        <x-detailed-information.key-value
            key="Текущее значение оплаты труда:"
            value="{{ $point->base_payment }}">
        </x-detailed-information.key-value>
    </div>

    @include('includes.filters.point-base-payment-history', ['point' => $point])

    <canvas id="base-payment-change-history-chart-canvas" height="200" style="height: 300px;"
            data-labels="{{ json_encode($timePoints) }}" data-values="{{ json_encode($values) }}"></canvas>
@endsection
