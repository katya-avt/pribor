@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::ITEMS_MANAGE)
        <div>
            <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Добавить изделие</a>
        </div>
    @endcan

    @include('includes.filters.item.item')

    <table class="table table-bordered" id="itemTable">
        <thead>
        <tr>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Группа</th>
            <th scope="col">Тип ДСЕ</th>
            <th scope="col">ЕдИзм</th>
            <th scope="col">Тип изготовления</th>
            @can(\App\Models\Users\Permission::ITEMS_MANAGE)
                <th scope="col" colspan="2">Изменить</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td><a href="{{ route('items.show', $item->id) }}">{{ $item->drawing }}</a></td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->group->name }}</td>
                <td>{{ $item->itemType->name }}</td>
                <td>{{ $item->unit->short_name }}</td>
                <td>{{ $item->manufactureType->name }}</td>
                @can(\App\Models\Users\Permission::ITEMS_MANAGE)
                    @if(!$item->added_to_order_at)
                        <td><a href="{{ route('items.edit', ['item' => $item->id]) }}"><i
                                    class="fas fa-edit text-success"></i></a>
                        </td>
                    @endif
                    <td>
                        <form action="{{ route('items.destroy', ['item' => $item->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-modals.modal
                                name="item-{{ $item->id }}"
                                size="lg"
                                title="Подтвердите удаление"
                                body="Вы уверены, что хотите удалить изделие с чертежом {{ $item->drawing }}?">
                            </x-modals.modal>
                        </form>
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
