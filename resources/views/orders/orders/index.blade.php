@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::ORDERS_MANAGE)
        @if($orderStatus == \App\Models\Orders\OrderStatus::PENDING_URL_PARAM_NAME)
            <div>
                <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">Добавить заказ</a>
            </div>
        @endif
    @endcan

    @include('includes.filters.order.order')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Код</th>
            <th scope="col">Наименование</th>
            <th scope="col">Дата</th>
            <th scope="col">Заказчик</th>
            @can(\App\Models\Users\Permission::ORDERS_MANAGE)
                @if($orderStatus == \App\Models\Orders\OrderStatus::PENDING_URL_PARAM_NAME)
                    <th scope="col" colspan="2">Изменить</th>
                @endif
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td><a href="{{ route('orders.order-items.index', $order->id) }}">{{ $order->code }}</a></td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->creation_date }}</td>
                <td>{{ $order->customer->name }}</td>
                @can(\App\Models\Users\Permission::ORDERS_MANAGE)
                    @if($orderStatus == \App\Models\Orders\OrderStatus::PENDING_URL_PARAM_NAME)
                        <td><a href="{{ route('orders.edit', ['order' => $order->id]) }}"><i
                                    class="fas fa-edit text-success"></i></a></td>
                        <td>
                            <form action="{{ route('orders.destroy', ['order' => $order->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="order-{{ $order->id }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить заказ с кодом {{ $order->code }}?">
                                </x-modals.modal>
                            </form>
                        </td>
                    @endif
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection
