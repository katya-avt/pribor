@extends('layouts.main')
@section('content')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Текущее кол-во</th>
            <th scope="col">Необходимое кол-во</th>
            <th scope="col">Точка заказа</th>
            <th scope="col">Недостающее кол-во</th>
        </tr>
        </thead>
        <tbody>

        @foreach($inOrderPurchasedItemsReachedItsOrderPoint as $inOrderPurchasedItemReachedItsOrderPoint)
            <tr>
                <td>
                    <a href="{{ route('items.show', $inOrderPurchasedItemReachedItsOrderPoint[0]->id) }}">{{ $inOrderPurchasedItemReachedItsOrderPoint[0]->drawing }}</a>
                </td>
                <td>{{ $inOrderPurchasedItemReachedItsOrderPoint[0]->name }}</td>
                <td>{{ $inOrderPurchasedItemReachedItsOrderPoint[0]->cnt }}</td>
                <td>{{ $inOrderPurchasedItemReachedItsOrderPoint[1]->cnt }}</td>
                <td>{{ $inOrderPurchasedItemReachedItsOrderPoint[0]->purchasedItem->order_point }}</td>
                <td>{{ $inOrderPurchasedItemReachedItsOrderPoint[1]->cnt + $inOrderPurchasedItemReachedItsOrderPoint[0]->purchasedItem->order_point - $inOrderPurchasedItemReachedItsOrderPoint[0]->cnt }}</td>
            </tr>
        @endforeach
        @foreach($outOfOrderPurchasedItemsReachedItsOrderPoint as $outOfOrderPurchasedItemReachedItsOrderPoint)
            <tr>
                <td>
                    <a href="{{ route('items.show', $outOfOrderPurchasedItemReachedItsOrderPoint->id) }}">{{ $outOfOrderPurchasedItemReachedItsOrderPoint->drawing }}</a>
                </td>
                <td>{{ $outOfOrderPurchasedItemReachedItsOrderPoint->name }}</td>
                <td>{{ $outOfOrderPurchasedItemReachedItsOrderPoint->cnt }}</td>
                <td>-</td>
                <td>{{ $outOfOrderPurchasedItemReachedItsOrderPoint->purchasedItem->order_point }}</td>
                <td>{{ $outOfOrderPurchasedItemReachedItsOrderPoint->purchasedItem->order_point - $outOfOrderPurchasedItemReachedItsOrderPoint->cnt }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
