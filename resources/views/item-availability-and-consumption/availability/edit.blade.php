@extends('layouts.main-for-modal')
@section('content')
    <form action="{{ route('item-availability-and-consumption.availability.update', ['item' => $item->id]) }}"
          method="post" id="itemCnt">
        @csrf
        @method('PATCH')

        <x-detailed-information.key-value
            key="Чертеж:"
            value="{{ $item->drawing }}">
        </x-detailed-information.key-value>

        <x-detailed-information.key-value
            key="Наименование:"
            value="{{ $item->name }}">
        </x-detailed-information.key-value>

        <div class="mt-3">
            <x-forms.inputs.input
                name="cnt"
                label="Кол-во:"
                type="text"
                message={{ $message }}
                    value="{{ $item->cnt }}"
                ajax=true>
            </x-forms.inputs.input>
        </div>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
