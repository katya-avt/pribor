@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col">
                <x-detailed-information.key-value
                    key="Чертеж:"
                    value="{{ $item->drawing }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Основная единица измерения:"
                    value="{{ $item->unit->short_name }}">
                </x-detailed-information.key-value>

                <div class="card border-dark mb-3" style="max-width: 25rem;">
                    <div class="card-header">Только для деталей</div>
                    <div class="card-body text-dark">
                        <x-detailed-information.key-value
                            key="Размер детали:"
                            value="{{ $item->detail ? $item->detail->detail_size : '' }}">
                        </x-detailed-information.key-value>

                        <x-detailed-information.key-value
                            key="Размер заготовки:"
                            value="{{ $item->detail ? $item->detail->billet_size : '' }}">
                        </x-detailed-information.key-value>

                        <x-detailed-information.key-value
                            key="Черн. вес:"
                            value="{{ $detailAdditionalInformation['blackWeight'] }}">
                        </x-detailed-information.key-value>

                        <x-detailed-information.key-value
                            key="Чист. вес:"
                            value="{{ $detailAdditionalInformation['netWeight'] }}">
                        </x-detailed-information.key-value>

                        <x-detailed-information.key-value
                            key="КИМ:"
                            value="{{ $detailAdditionalInformation['kim'] }}">
                        </x-detailed-information.key-value>
                    </div>
                </div>

                <div class="card border-dark mb-3" style="max-width: 25rem;">
                    <div class="card-header">
                        Действующие спецификации и маршрут
                        @can(\App\Models\Users\Permission::ITEMS_MANAGE)
                            <div>
                                <a href="{{ route('items.current-specifications-and-route.edit', $item->id) }}">
                                    Изменить</a>
                            </div>
                        @endcan
                    </div>
                    <div class="card-body text-dark">
                        <div class="row g-3 align-items-center mb-1">
                            <div class="col-auto fw-bold">
                                Спецификация состава:
                            </div>
                            <div class="col-auto">
                                @if($item->specification_number)
                                    <a href="{{ route('specifications.show', $item->specification_number) }}">
                                        {{ $item->specification_number }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 align-items-center mb-1">
                            <div class="col-auto fw-bold">
                                Спецификация покрытия:
                            </div>
                            <div class="col-auto">
                                @if($item->cover_number)
                                    <a href="{{ route('covers.show', $item->cover_number) }}">
                                        {{ $item->cover_number }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 align-items-center mb-1">
                            <div class="col-auto fw-bold">
                                Технологический маршрут:
                            </div>
                            <div class="col-auto">
                                @if($item->route_number)
                                    <a href="{{ route('routes.show', $item->route_number) }}">
                                        {{ $item->route_number }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-dark mb-3" style="max-width: 25rem;">
                    <div class="card-header">Список спецификаций и маршрутов</div>
                    <div class="card-body text-dark">
                        @if($item->itemType->isProprietary())
                            <div class="mb-1">
                                <a href="{{ route('items.specifications.index', $item->id) }}">Список спецификаций</a>
                            </div>
                        @endif
                        @if(!$item->group->isCover())
                            <div class="mb-1">
                                <a href="{{ route('items.covers.index', $item->id) }}">Список покрытий</a>
                            </div>
                        @endif
                        <div class="mb-1">
                            <a href="{{ route('items.routes.index', $item->id) }}">Список маршрутов</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-7">
                <x-detailed-information.key-value
                    key="Наименование:"
                    value="{{ $item->name }}">
                </x-detailed-information.key-value>

                <div class="row">
                    <div class="col">
                        <x-detailed-information.key-value
                            key="Тип номенкл-ры:"
                            value="{{ $item->itemType->name }}">
                        </x-detailed-information.key-value>
                    </div>
                    <div class="col">
                        <x-detailed-information.key-value
                            key="Группа:"
                            value="{{ $item->group->name }}">
                        </x-detailed-information.key-value>
                    </div>
                </div>

                <x-detailed-information.key-value
                    key="Основной склад:"
                    value="{{ $item->mainWarehouse->name }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Тип изготовления:"
                    value="{{ $item->manufactureType->name }}">
                </x-detailed-information.key-value>

                <div class="card border-dark mb-3" style="max-width: 40rem;">
                    <div class="card-header">Только для покупных</div>
                    <div class="card-body text-dark">
                        <div class="row">
                            <div class="col">
                                <x-detailed-information.key-value
                                    key="Цена покупки (за ед.):"
                                    value="{{ $item->purchasedItem ? $item->purchasedItem->purchase_price : '' }}">
                                </x-detailed-information.key-value>
                            </div>

                            <div class="col">
                                <x-detailed-information.key-value
                                    key="Цена покупки (за партию):"
                                    value="{{ $item->purchasedItem ? round($item->purchasedItem->purchase_price * $item->purchasedItem->purchase_lot, 2) : '' }}">
                                </x-detailed-information.key-value>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <x-detailed-information.key-value
                                    key="Партия закупки:"
                                    value="{{ $item->purchasedItem ? $item->purchasedItem->purchase_lot : '' }}">
                                </x-detailed-information.key-value>
                            </div>

                            <div class="col">
                                <x-detailed-information.key-value
                                    key="Ед/изм. закупки:"
                                    value="{{ $item->purchasedItem ? $item->purchasedItem->unit->short_name : '' }}">
                                </x-detailed-information.key-value>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <x-detailed-information.key-value
                                    key="Точка заказа:"
                                    value="{{ $item->purchasedItem ? $item->purchasedItem->order_point : '' }}">
                                </x-detailed-information.key-value>
                            </div>

                            <div class="col">
                                <x-detailed-information.key-value
                                    key="Коэфф. ед. изм.:"
                                    value="{{ $item->purchasedItem ? $item->purchasedItem->unit_factor : '' }}">
                                </x-detailed-information.key-value>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-dark mb-3" style="max-width: 20rem;">
                    <div class="card-header">Форма Д5</div>
                    <div class="card-body text-dark">
                        <div class="mb-1">
                            <a href="{{ route('items.form-d5.index', $item->id) }}">Форма Д5</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="card border-dark mb-3" style="max-width: 70rem;">
            <div class="card-header">Себестоимость изделия</div>
            <div class="card-body text-dark">
                <x-detailed-information.key-value
                    key="Общая себестоимость единицы:"
                    value="{{ round($materialsCost + $coverCost + $salary, 2) }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Себестоимость материалов:"
                    value="{{ round($materialsCost, 2) }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Себестоимость покрытия:"
                    value="{{ round($coverCost, 2) }}">
                </x-detailed-information.key-value>

                <x-detailed-information.key-value
                    key="Сдельная зарплата:"
                    value="{{ round($salary, 2) }}">
                </x-detailed-information.key-value>
            </div>
            <div class="card-footer text-dark">
                По цехам:
            </div>
            <div class="card-body text-dark">
                <div class="container">
                    <div class="row">
                        @foreach($departmentsCorrelation as $name => $correlation)
                            <div class="col">
                                <div class="fw-bold">{{ $name }}</div>
                                <div>{{ round($materialsCost * $correlation, 2) }}</div>
                                <div>{{ round($coverCost * $correlation, 2) }}</div>
                                <div>{{ round($salary * $correlation, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
