@extends('layouts.main')
@section('content')

    @include('includes.filters.order.item-consumption')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Код</th>
            <th scope="col">Наименование</th>
            <th scope="col">Дата</th>
            <th scope="col">Заказчик</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td><a href="{{ route('item-availability-and-consumption.consumption.show', $order->id) }}">{{ $order->code }}</a></td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->creation_date }}</td>
                <td>{{ $order->customer->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection
