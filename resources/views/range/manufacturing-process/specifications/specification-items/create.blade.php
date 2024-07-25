@extends('layouts.main')
@section('content')
    <form
        action="{{ route('specifications.specification-items.store', ['specification' => $specification->number]) }}"
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
            name="cnt"
            label="Кол-во:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Добавить"></x-forms.buttons.button>
    </form>
@endsection
