@extends('layouts.main')
@section('content')
    <form
        action="{{ route('covers.cover-items.store', ['cover' => $cover->number]) }}"
        method="post">
        @csrf
        <div class="input-group">
            <x-forms.inputs.input
                name="drawing"
                label="Чертеж:"
                type="text"
                message={{ $message }}>
            </x-forms.inputs.input>

            <x-modals.modal name="item" size="lg" title="Выберете изделие"></x-modals.modal>
        </div>

        <x-forms.inputs.input
            name="area"
            label="Площадь:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="consumption"
            label="Потребление:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Добавить"></x-forms.buttons.button>
    </form>
@endsection
