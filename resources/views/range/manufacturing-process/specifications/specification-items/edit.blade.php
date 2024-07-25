@extends('layouts.main')
@section('content')
    <form
        action="{{ route('specifications.specification-items.update', ['specification' => $specification->number, 'specificationItem' => $specificationItem->id]) }}"
        method="post">
        @csrf
        @method('PATCH')

        <div class="input-group">
            <x-forms.inputs.input
                name="drawing"
                label="Чертеж:"
                type="text"
                message={{ $message }}
                    value="{{ $specificationItemData->drawing }}">
            </x-forms.inputs.input>

            <x-modals.modal name="item" size="lg" title="Выберете изделие"></x-modals.modal>
        </div>

        <x-forms.inputs.input
            name="cnt"
            label="Кол-во:"
            type="text"
            message={{ $message }}
                value="{{ $specificationItemData->pivot->cnt }}">
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
