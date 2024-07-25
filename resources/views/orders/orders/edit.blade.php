@extends('layouts.main')
@section('content')
    <form action="{{ route('orders.update', ['order' => $order->id]) }}" method="post">
        @csrf
        @method('PATCH')

        <x-forms.inputs.input
            name="code"
            label="Код:"
            type="text"
            message={{ $message }}
                value="{{ $order->code }}">
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="name"
            label="Наименование:"
            type="text"
            message={{ $message }}
                value="{{ $order->name }}">
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="closing_date"
            label="Дата закрытия:"
            type="date"
            message={{ $message }}
                value="{{ date('Y-m-d', strtotime($order->closing_date)) }}">
        </x-forms.inputs.input>

        <div class="input-group mb-3">
            <x-forms.inputs.input
                name="customer_inn"
                label="Заказчик:"
                type="text"
                message={{ $message }}
                    value="{{ $order->customer->name }}">
            </x-forms.inputs.input>

            <x-modals.modal name="customer" size="lg" title="Выберете заказчика"></x-modals.modal>
        </div>

        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="note" class="col-form-label">Примечание:</label>
            </div>
            <div class="col-auto">
                <textarea
                    class="form-control"
                    name="note"
                    aria-label="Примечание:">{{ $order->note }}</textarea>
            </div>
        </div>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
