@extends('layouts.main')
@section('content')
    <form action="{{ route('orders.order-items.update', ['order' => $order->id, 'orderItem' => $item->id]) }}" method="post">
        @csrf
        @method('PATCH')

        <div class="input-group mb-3">
            <x-forms.inputs.input
                name="item_id"
                label="Изделие:"
                type="text"
                message={{ $message }}
                    value="{{ $item->drawing }}">
            </x-forms.inputs.input>

            <x-modals.modal name="orderItem" size="lg" title="Выберете изделие"></x-modals.modal>
        </div>

        <x-forms.inputs.input
            name="per_unit_price"
            label="Цена за единицу:"
            type="text"
            message={{ $message }}
                value="{{ $item->pivot->per_unit_price }}">
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="cnt"
            label="Кол-во:"
            type="text"
            message={{ $message }}
                value="{{ $item->pivot->cnt }}">
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
