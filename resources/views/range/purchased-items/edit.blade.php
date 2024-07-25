@extends('layouts.main')
@section('content')
    <form action="{{ route('purchased-items.update', ['item' => $item->id]) }}"
          method="post">
        @csrf
        @method('PATCH')

        <x-detailed-information.key-value
            key="Чертеж:"
            value="{{ $item->drawing }}">
        </x-detailed-information.key-value>

        <x-detailed-information.key-value
            key="Наименование:"
            value="{{ $item->name }}">
        </x-detailed-information.key-value>

        <div class="mt-3">
            <x-forms.inputs.input
                name="purchase_lot"
                label="Партия закупки:"
                type="text"
                message={{ $message }}
                    value="{{ $item->purchasedItem->purchase_lot }}">
            </x-forms.inputs.input>
        </div>

        <x-forms.inputs.input
            name="order_point"
            label="Точка заказа:"
            type="text"
            message={{ $message }}
                value="{{ $item->purchasedItem->order_point }}">
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
