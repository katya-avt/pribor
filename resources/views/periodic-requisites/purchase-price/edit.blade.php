@extends('layouts.main-for-modal')
@section('content')
    <form action="{{ route('periodic-requisites.purchase-price.update', ['item' => $item->id]) }}"
          method="post" id="purchasePrice">
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
                name="purchase_price"
                label="Цена покупки:"
                type="text"
                message={{ $message }}
                    value="{{ $item->purchasedItem->purchase_price }}"
                ajax=true>
            </x-forms.inputs.input>
        </div>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
