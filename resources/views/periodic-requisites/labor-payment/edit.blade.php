@extends('layouts.main-for-modal')
@section('content')
    <form action="{{ route('periodic-requisites.labor-payment.update', ['point' => $point->code]) }}"
          method="post" id="laborPayment">
        @csrf
        @method('PATCH')

        <x-detailed-information.key-value
            key="Код:"
            value="{{ $point->code }}">
        </x-detailed-information.key-value>

        <x-detailed-information.key-value
            key="Наименование:"
            value="{{ $point->name }}">
        </x-detailed-information.key-value>

        <div class="mt-3">
            <x-forms.inputs.input
                name="base_payment"
                label="Базовая ставка:"
                type="text"
                message={{ $message }}
                    value="{{ $point->base_payment }}"
                ajax=true>
            </x-forms.inputs.input>
        </div>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
