@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::COVERS_MANAGE)
        <div>
            <a href="{{ route('items.covers.create', ['item' => $item->id]) }}" class="btn btn-primary mb-3">Добавить
                покрытие</a>
        </div>
    @endcan

    <x-detailed-information.key-value
        key="Чертеж:"
        value="{{ $item->drawing }}">
    </x-detailed-information.key-value>

    <x-detailed-information.key-value
        key="Наименование:"
        value="{{ $item->name }}">
    </x-detailed-information.key-value>

    <table class="table table-bordered" id="coverTable">
        <thead>
        <tr>
            <th scope="col">Номер</th>
            @can(\App\Models\Users\Permission::COVERS_MANAGE)
                <th scope="col" class="col-1">Удалить</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($itemCovers as $itemCover)
            <tr>
                <td><a href="{{ route('covers.show', ['cover' => $itemCover->number]) }}">{{ $itemCover->number }}</a>
                </td>
                @can(\App\Models\Users\Permission::COVERS_MANAGE)
                    @if(!$itemCover->added_to_order_at)
                        <td>
                            <form
                                action="{{ route('items.covers.destroy', ['item' => $item->id, 'cover' => $itemCover->number]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="itemCover-{{ $itemCover->number }}-{{ $item->id }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить покрытие с номером {{ $itemCover->number }} у изделия с чертежом {{ $item->drawing }}?">
                                </x-modals.modal>
                            </form>
                        </td>
                    @endif
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
