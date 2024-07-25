@extends('layouts.admin.main')
@section('content')
    <form action="{{ route('admin.users.store') }}" method="post">
        @csrf

        <x-forms.inputs.input
            name="name"
            label="Имя:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="email"
            label="Email:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <x-forms.inputs.input
            name="password"
            label="Пароль:"
            type="password"
            message={{ $message }}>
        </x-forms.inputs.input>

        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <label for="password-confirm">Повторите пароль</label>
            </div>
            <div class="col-auto">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                       autocomplete="new-password">
            </div>
        </div>

        <x-forms.selects.select
            name="role_id"
            label="Роль"
            :options="$roles"
            key="name"
            message={{ $message }}>
        </x-forms.selects.select>

        <x-forms.buttons.button label="Добавить"></x-forms.buttons.button>
    </form>
@endsection
