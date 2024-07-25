@extends('layouts.main')
@section('content')
    <form action="{{ route('covers.store') }}" method="post">
        @csrf
        <x-forms.inputs.input
            name="number"
            label="Номер спецификации:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.buttons.button label="Добавить"></x-forms.buttons.button>
    </form>
@endsection
