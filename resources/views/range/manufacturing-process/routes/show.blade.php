@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::ROUTES_MANAGE)
        @if(!$route->added_to_order_at)
            <div>
                <a href="{{ route('routes.route-points.create', ['route' => $route->number]) }}"
                   class="btn btn-primary mb-3">Добавить точку маршрута</a>
            </div>
        @endif
    @endcan

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('routes.export', ['route' => $route->number]) }}">
            Скачать</a>
    </div>

    <x-detailed-information.key-value
        key="Номер маршрута:"
        value="{{ $route->number }}">
    </x-detailed-information.key-value>

    <x-detailed-information.key-value
        key="Расцеховка:"
        value="{{ $routeData['points']->pluck('code')->implode(' - ') }}">
    </x-detailed-information.key-value>

    @can(\App\Models\Users\Permission::ROUTES_MANAGE)
        @if(!$route->added_to_order_at)
            @if($routeData['points']->pluck('code')->count() > 1)
                <div class="mb-3">
                    <a href="{{ route('routes.route-points.rearrange', ['route' => $route->number]) }}">Изменить
                        порядок</a>
                </div>
            @endif
        @endif
    @endcan
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">N</th>
            <th scope="col">Точка маршрута</th>
            <th scope="col">Операция</th>
            <th scope="col">Т_Шт</th>
            <th scope="col">Т_Пов</th>
            <th scope="col">Т_ПЗ</th>
            <th scope="col">Ставка</th>
            @can(\App\Models\Users\Permission::ROUTES_MANAGE)
                @if(!$route->added_to_order_at)
                    <th scope="col" colspan="2">Изменить</th>
                @endif
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($routeData['points']->zip($routeData['operations']) as [$point, $operation])
            <tr>
                <td>{{ $point->pivot->point_number }}</td>
                <td>{{ $point->code }} - {{ $point->name }}</td>
                <td>{{ $operation->code }} - {{ $operation->name }}</td>
                <td>{{ $operation->pivot->unit_time }}</td>
                <td>{{ $operation->pivot->working_time }}</td>
                <td>{{ $operation->pivot->lead_time }}</td>
                <td>{{ $operation->pivot->rate_code }}</td>
                @can(\App\Models\Users\Permission::ROUTES_MANAGE)
                    @if(!$route->added_to_order_at)
                        <td>
                            <a href="{{ route('routes.route-points.edit', ['route' => $route->number, 'pointNumber' => $point->pivot->point_number]) }}"><i
                                    class="fas fa-edit text-success"></i></a>
                        </td>
                        <td>
                            <form
                                action="{{ route('routes.route-points.destroy', ['route' => $route->number, 'pointNumber' => $point->pivot->point_number]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="routePoint-{{ $point->pivot->point_number }}-{{ $route->number }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить точку '{{ $point->code }} - {{ $point->name }}' маршрута {{ $route->number }}?">
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
