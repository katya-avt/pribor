@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::ROUTES_MANAGE)
        <div>
            <a href="{{ route('items.routes.create', ['item' => $item->id]) }}" class="btn btn-primary mb-3">Добавить
                маршрут</a>
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

    <table class="table table-bordered" id="routeTable">
        <thead>
        <tr>
            <th scope="col">Номер</th>
            @can(\App\Models\Users\Permission::ROUTES_MANAGE)
                <th scope="col" class="col-1">Удалить</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($itemRoutes as $itemRoute)
            <tr>
                <td><a href="{{ route('routes.show', ['route' => $itemRoute->number]) }}">{{ $itemRoute->number }}</a>
                </td>
                @can(\App\Models\Users\Permission::ROUTES_MANAGE)
                    @if(!$itemRoute->added_to_order_at)
                        <td>
                            <form
                                action="{{ route('items.routes.destroy', ['item' => $item->id, 'route' => $itemRoute->number]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="itemRoute-{{ $itemRoute->number }}-{{ $item->id }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить маршрут с номером {{ $itemRoute->number }} у изделия с чертежом {{ $item->drawing }}?">
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
