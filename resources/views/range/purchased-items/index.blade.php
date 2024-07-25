@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @include('includes.filters.purchased-item.purchased-items')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Цена покупки</th>
            <th scope="col">Партия закупки</th>
            <th scope="col">Точка заказа</th>
            <th scope="col">Изменить</th>
        </tr>
        </thead>
        <tbody>
        @foreach($purchasedItems as $purchasedItem)
            <tr>
                <td><a href="{{ route('items.show', $purchasedItem->id) }}">{{ $purchasedItem->drawing }}</a></td>
                <td>{{ $purchasedItem->name }}</td>
                <td>{{ $purchasedItem->purchasedItem->purchase_price }}</td>
                <td>{{ $purchasedItem->purchasedItem->purchase_lot }}</td>
                <td>{{ $purchasedItem->purchasedItem->order_point }}</td>
                <td><a href="{{ route('purchased-items.edit', ['item' => $purchasedItem->id]) }}"><i
                            class="fas fa-edit text-success"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $purchasedItems->withQueryString()->links() }}
    </div>
@endsection
