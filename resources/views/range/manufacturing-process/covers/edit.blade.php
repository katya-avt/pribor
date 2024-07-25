@extends('layouts.main')
@section('content')
    <form action="{{ route('covers.update', ['cover' => $cover->number]) }}" method="post">
        @csrf
        @method('PATCH')

        <x-forms.inputs.input
            name="number"
            label="Номер спецификации:"
            type="text"
            message={{ $message }}
            value="{{ $cover->number }}">
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
