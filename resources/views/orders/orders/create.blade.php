@extends('layouts.main')
@section('content')
    <form action="{{ route('orders.store') }}" method="post">
        @csrf
        <x-forms.inputs.input
            name="code"
            label="Код:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="name"
            label="Наименование:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="closing_date"
            label="Дата закрытия:"
            type="date"
            message={{ $message }}>
        </x-forms.inputs.input>

        <div class="input-group">
            <x-forms.inputs.input
                name="customer_inn"
                label="Заказчик:"
                type="text"
                message={{ $message }}>
            </x-forms.inputs.input>

            <x-modals.modal name="customer" size="lg" title="Выберете заказчика"></x-modals.modal>
        </div>

        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <label for="note" class="col-form-label">Примечание:</label>
            </div>
            <div class="col-auto">
                <textarea
                    class="form-control"
                    name="note"
                    aria-label="Примечание:"></textarea>
            </div>
        </div>

        <x-forms.buttons.button label="Добавить"></x-forms.buttons.button>
    </form>
@endsection
