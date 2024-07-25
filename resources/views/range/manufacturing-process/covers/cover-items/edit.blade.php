@extends('layouts.main')
@section('content')
    <form
        action="{{ route('covers.cover-items.update', ['cover' => $cover->number, 'coverItem' => $coverItem->id]) }}"
        method="post">
        @csrf
        @method('PATCH')

        <div class="input-group">
            <x-forms.inputs.input
                name="drawing"
                label="Чертеж:"
                type="text"
                message={{ $message }}
                value="{{ $coverItemData->drawing }}">
            </x-forms.inputs.input>

            <x-modals.modal name="item" size="lg" title="Выберете изделие"></x-modals.modal>
        </div>

        <x-forms.inputs.input
            name="area"
            label="Площадь:"
            type="text"
            message={{ $message }}
            value="{{ $coverItemData->pivot->area }}">
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="consumption"
            label="Потребление:"
            type="text"
            message={{ $message }}
            value="{{ $coverItemData->pivot->consumption }}">
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
