@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::ROUTES_MANAGE)
        <div>
            <a href="{{ route('routes.create') }}" class="btn btn-primary mb-3">Добавить маршрут</a>
        </div>
    @endcan

    @include('includes.filters.route.route')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Номер</th>
            @can(\App\Models\Users\Permission::ROUTES_MANAGE)
                <th scope="col" colspan="2" class="col-1">Изменить</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($routes as $route)
            <tr>
                <td><a href="{{ route('routes.show', ['route' => $route->number]) }}">{{ $route->number }}</a></td>
                @can(\App\Models\Users\Permission::ROUTES_MANAGE)
                    @if(!$route->added_to_order_at)
                        <td><a href="{{ route('routes.edit', ['route' => $route->number]) }}"><i
                                    class="fas fa-edit text-success"></i></a>
                        </td>
                        <td>
                            <form action="{{ route('routes.destroy', ['route' => $route->number]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="routeNumber-{{ $route->number }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить маршрут с номером {{ $route->number }}?">
                                </x-modals.modal>
                            </form>
                        </td>
                    @endif
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $routes->withQueryString()->links() }}
    </div>
@endsection
