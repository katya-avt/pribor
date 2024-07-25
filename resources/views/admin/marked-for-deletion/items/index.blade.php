@extends('layouts.admin.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @include('includes.admin.filters.item')

    <table class="table table-bordered" id="itemTable">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Группа</th>
            <th scope="col">Тип ДСЕ</th>
            <th scope="col">ЕдИзм</th>
            <th scope="col">Тип изготовления</th>
            <th scope="col">Восстановить</th>
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
                <td>
                    <form action="{{ route('admin.marked-for-deletion.items.restore', ['item' => $item->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <x-modals.modal
                            name="markedForDeletionItem-{{ $item->id }}"
                            size="lg"
                            title="Подтвердите восстановление"
                            body="Вы уверены, что хотите восстановить изделие с чертежом {{ $item->drawing }}?"
                            confirmType="restore">
                        </x-modals.modal>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $items->withQueryString()->links() }}
    </div>
@endsection
