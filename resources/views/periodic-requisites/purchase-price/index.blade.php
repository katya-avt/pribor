@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @include('includes.filters.purchased-item.periodic-requisites')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col" colspan="2">Цена покупки</th>
        </tr>
        </thead>
        <tbody>
        @foreach($purchasedItems as $purchasedItem)
            <tr>
                <td><a href="{{ route('periodic-requisites.purchase-price.show', $purchasedItem->id) }}">{{ $purchasedItem->drawing }}</a></td>
                <td>{{ $purchasedItem->name }}</td>
                <td>{{ $purchasedItem->purchasedItem->purchase_price }}</td>
                @can(\App\Models\Users\Permission::PERIODIC_REQUISITES_MANAGE)
                    <td>
                        <x-modals.modal
                            name="purchasePrice"
                            size="lg"
                            title="Изменение цены покупки"
                            change="{{ $purchasedItem->id }}">
                        </x-modals.modal>
                    </td>
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $purchasedItems->withQueryString()->links() }}
    </div>
@endsection
