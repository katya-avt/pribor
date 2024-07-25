@extends('layouts.main')
@section('content')
    <form
        action="{{ route('routes.route-points.update', ['route' => $route->number, 'pointNumber' => $pointNumber]) }}"
        method="post">
        @csrf
        @method('PATCH')

        <div class="input-group">

            <x-forms.inputs.input
                name="point_code"
                label="Точка маршрута:"
                type="text"
                message={{ $message }}
                    value="{{ $routePointData->code }}">
            </x-forms.inputs.input>

            <x-modals.modal name="point" size="lg" title="Выберете точку маршрута"></x-modals.modal>
        </div>

        <div class="input-group">

            <x-forms.inputs.input
                name="operation_code"
                label="Операция:"
                type="text"
                message={{ $message }}
                    value="{{ $routePointData->pivot->operation_code }}">
            </x-forms.inputs.input>

            <x-modals.modal name="operation" size="lg" title="Выберете операцию"></x-modals.modal>
        </div>

        <x-forms.inputs.input
            name="unit_time"
            label="Т_Шт:"
            type="text"
            message={{ $message }}
                value="{{ $routePointData->pivot->unit_time }}">
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="working_time"
            label="Т_Пов:"
            type="text"
            message={{ $message }}
                value="{{ $routePointData->pivot->working_time }}">
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="lead_time"
            label="Т_ПЗ:"
            type="text"
            message={{ $message }}
                value="{{ $routePointData->pivot->lead_time }}">
        </x-forms.inputs.input>

        <x-forms.selects.select
            name="rate_code"
            label="Ставка"
            :options="$rates"
            key="code"
            message={{ $message }}
                oldValue="{{ $routePointData->pivot->rate_code }}">
        </x-forms.selects.select>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
