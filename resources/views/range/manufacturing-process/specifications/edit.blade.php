@extends('layouts.main')
@section('content')
    <form action="{{ route('specifications.update', ['specification' => $specification->number]) }}" method="post">
        @csrf
        @method('PATCH')

        <x-forms.inputs.input
            name="number"
            label="Номер спецификации:"
            type="text"
            message={{ $message }}
                value="{{ $specification->number }}">
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
