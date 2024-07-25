@extends('layouts.main')
@section('content')
    <form action="{{ route('routes.update', ['route' => $route->number]) }}" method="post">
        @csrf
        @method('PATCH')

        <x-forms.inputs.input
            name="number"
            label="Номер маршрута:"
            type="text"
            message={{ $message }}
                value="{{ $route->number }}">
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
