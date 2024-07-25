@if($descendant->items->isNotEmpty())
    <tr class="d-none {{ $orderItemDetail->order_item_specification_id }}">
        <th scope="col" class="align-middle">
            <button class="order-item-details-btn btn" data-id="{{ $descendant->order_item_specification_id }}">+
            </button>{{ $descendant->component->drawing }}</th>
        <th scope="col" class="align-middle">{{ $descendant->component->name }}</th>
        <th scope="col" class="align-middle">{{ $descendant->component_cnt }}</th>
        <th scope="col" class="align-middle">{{ $descendant->component->cnt }}</th>
        <th scope="col" class="align-middle">{{ $descendant->component->cnt < \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemComponentTotalRequiredCnt($descendant->component->id, $descendant->order_item_specification_id) ? \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemComponentTotalRequiredCnt($descendant->component->id, $descendant->order_item_specification_id) - $descendant->component->cnt : '-' }}</th>
    </tr>
@else
    <tr class="d-none {{ $orderItemDetail->order_item_specification_id }}">
        <td class="align-middle">{{ $descendant->component->drawing }}</td>
        <td class="align-middle">{{ $descendant->component->name }}</td>
        <td class="align-middle">{{ $descendant->component_cnt }}</td>
        <td class="align-middle">{{ $descendant->component->cnt }}</td>
        <td class="align-middle">{{ $descendant->component->cnt < \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemComponentTotalRequiredCnt($descendant->component->id, $descendant->order_item_specification_id) ? \App\Services\ItemAvailabilityAndConsumption\Consumption\Service::getOrderItemComponentTotalRequiredCnt($descendant->component->id, $descendant->order_item_specification_id) - $descendant->component->cnt : '-' }}</td>
    </tr>
@endif

@if($descendant->items->isNotEmpty())
    @foreach($descendant->items as $nextDescendant)
        @include('item-availability-and-consumption.consumption.descendant', ['orderItemDetail'=> $descendant, 'descendant' => $nextDescendant])
    @endforeach
@endif
