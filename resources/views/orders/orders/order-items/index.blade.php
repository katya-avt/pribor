@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @switch($order->status->id)
        @case(\App\Models\Orders\OrderStatus::PENDING)
        @can(\App\Models\Users\Permission::PUT_ORDER_INTO_PRODUCTION)
            @if($order->items->isNotEmpty())
                <form action="{{ route('orders.put-into-production', ['order' => $order->id]) }}"
                      method="post">
                    @csrf
                    @method('PATCH')
                    <x-forms.buttons.button label="В производство"></x-forms.buttons.button>
                </form>
            @endif
        @endcan
        @break

        @case(\App\Models\Orders\OrderStatus::IN_PRODUCTION)
        @can(\App\Models\Users\Permission::COMPLETE_ORDER_PRODUCTION)
            <form action="{{ route('orders.complete-production', ['order' => $order->id]) }}"
                  method="post">
                @csrf
                @method('PATCH')
                <x-forms.buttons.button label="Завершить производство"></x-forms.buttons.button>
            </form>
        @endcan
        @break

        @case(\App\Models\Orders\OrderStatus::PRODUCTION_COMPLETED)
        @can(\App\Models\Users\Permission::SEND_ORDER_ON_SHIPMENT)
            <form action="{{ route('orders.send-on-shipment', ['order' => $order->id]) }}"
                  method="post">
                @csrf
                @method('PATCH')
                <x-forms.buttons.button label="На отгрузку"></x-forms.buttons.button>
            </form>
        @endcan
        @break

        @case(\App\Models\Orders\OrderStatus::ON_SHIPMENT)
        @can(\App\Models\Users\Permission::SHIP_ORDER)
            <form action="{{ route('orders.ship', ['order' => $order->id]) }}"
                  method="post">
                @csrf
                @method('PATCH')
                <x-forms.buttons.button label="Отгрузить"></x-forms.buttons.button>
            </form>
        @endcan
        @break
    @endswitch

    <div class="container-fluid mt-3">
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
                <x-detailed-information.key-value
                    key="Дата завершения:"
                    value="{{ $order->completion_date }}">
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

    <div class="card border-dark mb-3" style="max-width: 20rem;">
        <div class="card-header">Распределение трудоемкости и прибыли</div>
        <div class="card-body text-dark">
            @foreach($laborIntensityAndProfitDistribution as $department => $distribution)
                <x-detailed-information.key-value
                    key="{{ $department }}"
                    value="{{ round($distribution, 2) }}">
                </x-detailed-information.key-value>
            @endforeach
        </div>
    </div>

    <x-detailed-information.key-value
        key="Примечание:"
        value="{{ $order->note }}">
    </x-detailed-information.key-value>

    <div class="card border-dark mb-3" style="max-width: 50rem;">
        <div class="card-header">Спецификация заказа</div>
        <div class="card-body text-dark">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        {{--                        Сумма (цена за единицу * кол-во)--}}
                        <x-detailed-information.key-value
                            key="Сумма:"
                            value="{{ $order->amount }}">
                        </x-detailed-information.key-value>

                        {{--                        Сумма (того, что относится к заказу в order_item_specification и того, что относится к заказу--}}
                        {{--                        в order_item_route: ставка*оплата*сумма времени)--}}
                        <x-detailed-information.key-value
                            key="Себестоимость:"
                            value="{{ $order->cost }}">
                        </x-detailed-information.key-value>
                    </div>
                    <div class="col">
                        {{--                        Сумма - себестоимость--}}
                        <x-detailed-information.key-value
                            key="Ожидаемая прибыль:"
                            value="{{ round($order->amount - $order->cost, 2) }}">
                        </x-detailed-information.key-value>

                        {{--                        Ожидаемая прибыль/Сумма * 100--}}
                        <x-detailed-information.key-value
                            key="Рентабельность (%):"
                            value="{{ $order->amount == 0 ? 0 : round((($order->amount - $order->cost)/$order->amount) * 100, 2) }}">
                        </x-detailed-information.key-value>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can(\App\Models\Users\Permission::ORDERS_MANAGE)
        @if($order->isPending())
            <div>
                <a href="{{ route('orders.order-items.create', ['order' => $order->id]) }}"
                   class="btn btn-primary my-3">Добавить изделие</a>
            </div>
        @endif
    @endcan

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Изделие</th>
            <th scope="col">Наименование</th>
            <th scope="col">Кол-во</th>
            <th scope="col">ЕдИзм</th>
            <th scope="col">НДС(%)</th>
            <th scope="col">Без НДС</th>
            <th scope="col">Цена за ед</th>
            <th scope="col">Сумма</th>
            <th scope="col">Себестоимость</th>
            @can(\App\Models\Users\Permission::ORDERS_MANAGE)
                @if($order->isPending())
                    <th scope="col" colspan="2">Изменить</th>
                @endif
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($orderItems as $orderItem)
            <tr>
                <td><a href="{{ route('items.show', $orderItem->id) }}">{{ $orderItem->drawing }}</a></td>
                <td>{{ $orderItem->name }}</td>
                <td>{{ $orderItem->pivot->cnt }}</td>
                <td>{{ $orderItem->unit->short_name }}</td>
                <td>20</td>
                <td>{{ round($orderItem->pivot->amount - ($orderItem->pivot->amount * (20/120)), 2) }}</td>
                <td>{{ $orderItem->pivot->per_unit_price }}</td>
                <td>{{ $orderItem->pivot->amount }}</td>
                <td>{{ $orderItem->pivot->cost }}</td>
                @can(\App\Models\Users\Permission::ORDERS_MANAGE)
                    @if($order->isPending())
                        <td>
                            <a href="{{ route('orders.order-items.edit', ['order' => $order->id, 'orderItem' => $orderItem->id]) }}"><i
                                    class="fas fa-edit text-success"></i></a>
                        </td>
                        <td>
                            <form
                                action="{{ route('orders.order-items.destroy', ['order' => $order->id, 'orderItem' => $orderItem->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="orderItem-{{ $order->id }}-{{ $orderItem->id }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить изделие с чертежом {{ $orderItem->drawing }} из заказа с кодом {{ $order->code }}?">
                                </x-modals.modal>
                            </form>
                        </td>
                    @endif
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
