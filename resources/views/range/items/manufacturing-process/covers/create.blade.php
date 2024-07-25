@extends('layouts.main')
@section('content')
    <form action="{{ route('items.covers.store', ['item' => $item->id]) }}" method="post">
        @csrf
        <div class="input-group">
            <x-forms.inputs.input
                name="number"
                label="Номер спецификации:"
                type="text"
                message={{ $message }}>
            </x-forms.inputs.input>

            <x-modals.modal name="cover" size="lg" title="Выберете спецификацию покрытия"></x-modals.modal>
        </div>

        <x-forms.buttons.button label="Добавить"></x-forms.buttons.button>
    </form>
@endsection
