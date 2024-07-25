@extends('layouts.main')
@section('content')
    <form action="{{ route('items.current-specifications-and-route.update', $item->id) }}" method="post">
        @csrf
        @method('PATCH')
        <div class="input-group">
            <x-forms.inputs.input
                name="specification_number"
                label="Номер спецификации:"
                type="text"
                message={{ $message }}
                    value="{{ $item->specification_number }}">
            </x-forms.inputs.input>

            <x-modals.modal
                name="itemCurrentSpecification"
                size="lg"
                title="Выберете спецификацию"
                itemId="{{ $item->id }}">
            </x-modals.modal>
        </div>

        <div class="input-group">
            <x-forms.inputs.input
                name="cover_number"
                label="Номер покрытия:"
                type="text"
                message={{ $message }}
                    value="{{ $item->cover_number }}">
            </x-forms.inputs.input>

            <x-modals.modal
                name="itemCurrentCover"
                size="lg"
                title="Выберете покрытие"
                itemId="{{ $item->id }}">
            </x-modals.modal>
        </div>

        <div class="input-group">
            <x-forms.inputs.input
                name="route_number"
                label="Номер маршрута:"
                type="text"
                message={{ $message }}
                    value="{{ $item->route_number }}">
            </x-forms.inputs.input>

            <x-modals.modal
                name="itemCurrentRoute"
                size="lg"
                title="Выберете маршрут"
                itemId="{{ $item->id }}">
            </x-modals.modal>
        </div>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
