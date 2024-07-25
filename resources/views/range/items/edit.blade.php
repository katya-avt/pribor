@extends('layouts.main')
@section('content')
    <form
        action="{{ route('items.update', ['item' => $item]) }}"
        method="post">
        @csrf
        @method('PATCH')

        <x-forms.inputs.input
            name="item[drawing]"
            label="Чертеж:"
            type="text"
            message={{ $message }}
                value="{{ $item->drawing }}"
            nestedArrayValue="item.drawing">
        </x-forms.inputs.input>

        <div class="input-group">
            <x-forms.inputs.input
                name="item[unit_code]"
                label="Основная единица измерения:"
                type="text"
                message={{ $message }}
                    value="{{ $item->unit->short_name }}"
                nestedArrayValue="item.unit_code">
            </x-forms.inputs.input>

            <x-modals.modal name="unit" size="lg" title="Выберете единицу измерения"></x-modals.modal>
        </div>

        <div class="card border-dark mb-3" style="max-width: 40rem;">
            <div class="card-header">Только для деталей</div>
            <div class="card-body text-dark">
                <x-forms.inputs.input
                    name="detail[detail_size]"
                    label="Размер детали:"
                    type="text"
                    message={{ $message }}
                        value="{{ $item->detail->detail_size ?? '' }}"
                    nestedArrayValue="detail.detail_size">
                </x-forms.inputs.input>

                <x-forms.inputs.input
                    name="detail[billet_size]"
                    label="Размер заготовки:"
                    type="text"
                    message={{ $message }}
                        value="{{ $item->detail->billet_size ?? '' }}"
                    nestedArrayValue="detail.billet_size">
                </x-forms.inputs.input>
            </div>
        </div>

        <x-forms.inputs.input
            name="item[name]"
            label="Наименование:"
            type="text"
            message={{ $message }}
                value="{{ $item->name }}"
            nestedArrayValue="item.name">
        </x-forms.inputs.input>


        <div class="input-group">
            <x-forms.inputs.input
                name="item[group_id]"
                label="Группа:"
                type="text"
                message={{ $message }}
                    value="{{ $item->group->name }}"
                nestedArrayValue="item.group_id">
            </x-forms.inputs.input>

            <x-modals.modal name="group" size="lg" title="Выберете группу"></x-modals.modal>
        </div>

        <div class="input-group">
            <x-forms.inputs.input
                name="item[main_warehouse_code]"
                label="Основной склад:"
                type="text"
                message={{ $message }}
                    value="{{ $item->mainWarehouse->name }}"
                nestedArrayValue="item.main_warehouse_code">
            </x-forms.inputs.input>

            <x-modals.modal name="mainWarehouse" size="lg" title="Выберете основной склад"></x-modals.modal>
        </div>

        <div class="input-group">
            <x-forms.inputs.input
                name="item[item_type_id]"
                label="Тип номенклатуры:"
                type="text"
                message={{ $message }}
                    value="{{ $item->itemType->name }}"
                nestedArrayValue="item.item_type_id">
            </x-forms.inputs.input>

            <x-modals.modal name="itemType" size="lg" title="Выберете тип номенклатуры"></x-modals.modal>
        </div>

        <div class="input-group">
            <x-forms.inputs.input
                name="item[manufacture_type_id]"
                label="Тип изготовления:"
                type="text"
                message={{ $message }}
                    value="{{ $item->manufactureType->name }}"
                nestedArrayValue="item.manufacture_type_id">
            </x-forms.inputs.input>

            <x-modals.modal name="manufactureType" size="lg" title="Выберете тип изготовления"></x-modals.modal>
        </div>

        <div class="card border-dark mb-3" style="max-width: 60rem;">
            <div class="card-header">Только для покупных</div>
            <div class="card-body text-dark">
                <x-forms.inputs.input
                    name="purchased[purchase_price]"
                    label="Цена покупки:"
                    type="text"
                    message={{ $message }}
                        value="{{ $item->purchasedItem->purchase_price ?? '' }}"
                    nestedArrayValue="purchased.purchase_price">
                </x-forms.inputs.input>

                <x-forms.inputs.input
                    name="purchased[purchase_lot]"
                    label="Партия закупки:"
                    type="text"
                    message={{ $message }}
                        value="{{ $item->purchasedItem->purchase_lot ?? '' }}"
                    nestedArrayValue="purchased.purchase_lot">
                </x-forms.inputs.input>

                <div class="input-group">
                    <x-forms.inputs.input
                        name="purchased[unit_code]"
                        label="Ед/изм. закупки:"
                        type="text"
                        message={{ $message }}
                            value="{{ $item->purchasedItem->unit->short_name ?? '' }}"
                        nestedArrayValue="purchased.unit_code">
                    </x-forms.inputs.input>

                    <x-modals.modal name="purchaseUnit" size="lg" title="Выберете единицу измерения"></x-modals.modal>
                </div>

                <x-forms.inputs.input
                    name="purchased[unit_factor]"
                    label="Коэфф. ед. изм.:"
                    type="text"
                    message={{ $message }}
                        value="{{ $item->purchasedItem->unit_factor ?? '' }}"
                    nestedArrayValue="purchased.unit_factor">
                </x-forms.inputs.input>

                <x-forms.inputs.input
                    name="purchased[order_point]"
                    label="Точка заказа:"
                    type="text"
                    message={{ $message }}
                        value="{{ $item->purchasedItem->order_point ?? '' }}"
                    nestedArrayValue="purchased.order_point">
                </x-forms.inputs.input>
            </div>
        </div>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
