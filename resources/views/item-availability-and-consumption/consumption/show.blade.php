@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <x-detailed-information.key-value
                    key="Код:"
                    value="{{ $order->code }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Дата создания:"
                    value="{{ $order->creation_date }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Дата закрытия:"
                    value="{{ $order->closing_date }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Дата запуска:"
                    value="{{ $order->launch_date }}">
                </x-detailed-information.key-value>
            </div>
            <div class="col">
                <x-detailed-information.key-value
                    key="Наименование:"
                    value="{{ $order->name }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Заказчик:"
                    value="{{ $order->customer->name }}">
                </x-detailed-information.key-value>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Необходимое кол-во</th>
            <th scope="col">Текущее кол-во</th>
            <th scope="col">Дефицит</th>
        </tr>
        </thead>
        <tbody>
        @if($orderItems->isNotEmpty())
            @foreach($orderItems as $itemId => $details)
                <th scope="col" class="align-middle">
                    <button class="order-item-details-btn btn"
                            data-id="{{ $itemId }}">+
                    </button>{{ $details[0]->orderItem->drawing }}</th>
                <th scope="col" class="align-middle">{{ $details[0]->orderItem->name }}</th>
                <th scope="col" class="align-middle">{{ \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getCntByOrderIdAndItemId($order->id, $itemId) }}</th>
                <th scope="col" class="align-middle">{{ $details[0]->orderItem->cnt }}</th>
                <th scope="col" class="align-middle">{{ $details[0]->orderItem->cnt < \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemTotalRequiredCnt($order->id, $itemId) ? \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemTotalRequiredCnt($order->id, $itemId) - $details[0]->orderItem->cnt : '-' }}</th>

                @foreach($details as $orderItemDetail)
                    @if($orderItemDetail->descendants->isNotEmpty())
                        <tr class="d-none {{ $orderItemDetail->current_item_id }}">
                            <th scope="col" class="align-middle">
                                <button class="order-item-details-btn btn"
                                        data-id="{{ $orderItemDetail->order_item_specification_id }}">+
                                </button>{{ $orderItemDetail->component->drawing }}</th>
                            <th scope="col" class="align-middle">{{ $orderItemDetail->component->name }}</th>
                            <th scope="col" class="align-middle">{{ $orderItemDetail->component_cnt }}</th>
                            <th scope="col" class="align-middle">{{ $orderItemDetail->component->cnt }}</th>
                            <th scope="col" class="align-middle">{{ $orderItemDetail->component->cnt < \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemComponentTotalRequiredCnt($orderItemDetail->component->id, $orderItemDetail->order_item_specification_id) ? \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemComponentTotalRequiredCnt($orderItemDetail->component->id, $orderItemDetail->order_item_specification_id) - $orderItemDetail->component->cnt : '-' }}</th>
                        </tr>
                    @else
                        <tr class="d-none {{ $orderItemDetail->current_item_id }}">
                            <td class="align-middle">{{ $orderItemDetail->component->drawing }}</td>
                            <td class="align-middle">{{ $orderItemDetail->component->name }}</td>
                            <td class="align-middle">{{ $orderItemDetail->component_cnt }}</td>
                            <td class="align-middle">{{ $orderItemDetail->component->cnt }}</td>
                            <td class="align-middle">{{ $orderItemDetail->component->cnt < \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemComponentTotalRequiredCnt($orderItemDetail->component->id, $orderItemDetail->order_item_specification_id) ? \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemComponentTotalRequiredCnt($orderItemDetail->component->id, $orderItemDetail->order_item_specification_id) - $orderItemDetail->component->cnt : '-' }}</td>
                        </tr>
                    @endif
                    @foreach($orderItemDetail->descendants as $descendant)
                        @include('item-availability-and-consumption.consumption.descendant', ['orderItem'=> $orderItemDetail, 'descendant' => $descendant])
                    @endforeach
                @endforeach
            @endforeach
        @endif

        @if($orderItemWithoutDetailsIdsCollection->isNotEmpty())
            @foreach($order->items->whereIn('id', $orderItemWithoutDetailsIdsCollection->toArray()) as $orderItem)
                <tr>
                    <th scope="col" class="align-middle">{{ $orderItem->drawing }}</th>
                    <th scope="col" class="align-middle">{{ $orderItem->name }}</th>
                    <th scope="col" class="align-middle">{{ \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getCntByOrderIdAndItemId($order->id, $orderItem->id) }}</th>
                    <th scope="col" class="align-middle">{{ $orderItem->cnt }}</th>
                    <th scope="col" class="align-middle">{{ $orderItem->cnt < \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemTotalRequiredCnt($order->id, $orderItem->id) ? \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemTotalRequiredCnt($order->id, $orderItem->id) - $orderItem->cnt : '-' }}</th>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
