@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @include('includes.filters.item.item-availability')

    <table class="table table-bordered" id="itemTable">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col" colspan="2">Кол-во</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td><a href="{{ route('items.show', $item->id) }}">{{ $item->drawing }}</a></td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->cnt }}</td>
                @can(\App\Models\Users\Permission::ITEMS_IN_STOCK_MANAGE)
                    <td>
                        <x-modals.modal
                            name="itemCnt"
                            size="lg"
                            title="Изменение кол-ва"
                            change="{{ $item->id }}">
                        </x-modals.modal>
                    </td>
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $items->withQueryString()->links() }}
    </div>
@endsection
