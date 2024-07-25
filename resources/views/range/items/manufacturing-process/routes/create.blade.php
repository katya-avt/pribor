@extends('layouts.main')
@section('content')
    <form action="{{ route('items.routes.store', ['item' => $item->id]) }}" method="post">
        @csrf
        <div class="input-group">
            <x-forms.inputs.input
                name="number"
                label="Номер маршрута:"
                type="text"
                message={{ $message }}>
            </x-forms.inputs.input>

            <x-modals.modal name="route" size="lg" title="Выберете маршрут"></x-modals.modal>
        </div>

        <x-forms.buttons.button label="Добавить"></x-forms.buttons.button>
    </form>
@endsection
