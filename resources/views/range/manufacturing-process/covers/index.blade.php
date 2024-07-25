@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::COVERS_MANAGE)
        <div>
            <a href="{{ route('covers.create') }}" class="btn btn-primary mb-3">Добавить спецификацию</a>
        </div>
    @endcan

    @include('includes.filters.cover.cover')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Номер</th>
            @can(\App\Models\Users\Permission::COVERS_MANAGE)
                <th scope="col" colspan="2" class="col-1">Изменить</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($covers as $cover)
            <tr>
                <td><a href="{{ route('covers.show', ['cover' => $cover->number]) }}">{{ $cover->number }}</a></td>
                @can(\App\Models\Users\Permission::COVERS_MANAGE)
                    @if(!$cover->added_to_order_at)
                        <td><a href="{{ route('covers.edit', ['cover' => $cover->number]) }}"><i
                                    class="fas fa-edit text-success"></i></a>
                        </td>
                        <td>
                            <form action="{{ route('covers.destroy', ['cover' => $cover->number]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="coverNumber-{{ $cover->number }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить покрытие с номером {{ $cover->number }}?">
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
        {{ $covers->withQueryString()->links() }}
    </div>
@endsection
