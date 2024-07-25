@extends('layouts.main-for-modal')
@section('content')

    @include('includes.filters.item.item-choice')

    <table class="table table-bordered" id="itemTable">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Группа</th>
            <th scope="col">Тип ДСЕ</th>
            <th scope="col">ЕдИзм</th>
            <th scope="col">Тип изготовления</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->drawing }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->group->name }}</td>
                <td>{{ $item->itemType->name }}</td>
                <td>{{ $item->unit->short_name }}</td>
                <td>{{ $item->manufactureType->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $items->withQueryString()->links() }}
    </div>
@endsection
