@extends('layouts.main')
@section('content')
    <form action="{{ route('orders.order-items.store', ['order' => $order->id]) }}" method="post">
        @csrf
        <div class="input-group">
            <x-forms.inputs.input
                name="item_id"
                label="Изделие:"
                type="text"
                message={{ $message }}>
            </x-forms.inputs.input>

            <x-modals.modal name="orderItem" size="lg" title="Выберете изделие"></x-modals.modal>
        </div>

        <x-forms.inputs.input
            name="per_unit_price"
            label="Цена за единицу:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="cnt"
            label="Кол-во:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Добавить"></x-forms.buttons.button>
    </form>
@endsection
