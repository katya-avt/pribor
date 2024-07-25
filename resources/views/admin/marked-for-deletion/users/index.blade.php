@extends('layouts.admin.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Имя</th>
            <th scope="col">Почта</th>
            <th scope="col">Роль</th>
            <th scope="col">Восстановить</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->name }}</td>
                <td>
                    <form action="{{ route('admin.marked-for-deletion.users.restore', ['user' => $user->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <x-modals.modal
                            name="markedForDeletionUser-{{ $user->id }}"
                            size="lg"
                            title="Подтвердите восстановление"
                            body="Вы уверены, что хотите восстановить пользователя {{ $user->name }}?"
                            confirmType="restore">
                        </x-modals.modal>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
