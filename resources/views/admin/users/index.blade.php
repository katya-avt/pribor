@extends('layouts.admin.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Добавить пользователя</a>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Имя</th>
            <th scope="col">Почта</th>
            <th scope="col">Роль</th>
            <th scope="col" colspan="2">Изменить</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->name }}</td>
                <td><a href="{{ route('admin.users.edit', ['user' => $user->id]) }}"><i
                            class="fas fa-edit text-success"></i></a>
                </td>
                <td>
                    <form action="{{ route('admin.users.destroy', ['user' => $user->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-modals.modal
                            name="user-{{ $user->id }}"
                            size="lg"
                            title="Подтвердите удаление"
                            body="Вы уверены, что хотите удалить пользователя {{ $user->name }}?">
                        </x-modals.modal>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
