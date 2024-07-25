@extends('layouts.main')
@section('content')

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('items.form-d5.export', ['item' => $item->id]) }}">
            Скачать</a>
    </div>

    <x-detailed-information.key-value
        key="Чертеж:"
        value="{{ $item->drawing }}">
    </x-detailed-information.key-value>

    <x-detailed-information.key-value
        key="Наименование:"
        value="{{ $item->name }}">
    </x-detailed-information.key-value>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Кол-во</th>
            <th scope="col">Ед.Изм.</th>
            <th scope="col">Номер спец.</th>
        </tr>
        </thead>
        <tbody>
        @foreach($itemsThatContainItem as $itemThatContainItem)
            <tr>
                <td>
                    <a href="{{ route('items.show', ['item' => $itemThatContainItem->id]) }}">{{ $itemThatContainItem->drawing }}</a>
                </td>
                <td>{{ $itemThatContainItem->name }}</td>
                <td>{{ $itemThatContainItem->cnt }}</td>
                <td>{{ $item->unit->short_name }}</td>
                @if($item->group->isCover())
                    <td>
                        <a href="{{ route('covers.show', ['cover' => $itemThatContainItem->number]) }}">
                            {{ $itemThatContainItem->number }}</a>
                    </td>
                @else
                    <td><a href="{{ route('specifications.show', ['specification' => $itemThatContainItem->number]) }}">
                            {{ $itemThatContainItem->number }}</a>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
