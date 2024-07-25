@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::COVERS_MANAGE)
        @if(!$cover->added_to_order_at)
            <div>
                <a href="{{ route('covers.cover-items.create', ['cover' => $cover->number]) }}"
                   class="btn btn-primary mb-3">Добавить изделие</a>
            </div>
        @endif
    @endcan

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('covers.export', ['cover' => $cover->number]) }}">
            Скачать</a>
    </div>

    <x-detailed-information.key-value
        key="Номер спецификации:"
        value="{{ $cover->number }}">
    </x-detailed-information.key-value>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Г</th>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Площадь</th>
            <th scope="col">Потребление</th>
            <th scope="col">Ед.изм.</th>
            @can(\App\Models\Users\Permission::COVERS_MANAGE)
                @if(!$cover->added_to_order_at)
                    <th scope="col" colspan="2">Изменить</th>
                @endif
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($coverData as $coverRow)
            <tr>
                <td>{{ $coverRow->group->name }}</td>
                <td><a href="{{ route('items.show', $coverRow->id) }}">{{ $coverRow->drawing }}</a></td>
                <td>{{ $coverRow->name }}</td>
                <td>{{ $coverRow->pivot->area }}</td>
                <td>{{ $coverRow->pivot->consumption }}</td>
                <td>{{ $coverRow->unit->short_name }}</td>
                @can(\App\Models\Users\Permission::COVERS_MANAGE)
                    @if(!$cover->added_to_order_at)
                        <td>
                            <a href="{{ route('covers.cover-items.edit', ['cover' => $cover->number, 'coverItem' => $coverRow->id]) }}"><i
                                    class="fas fa-edit text-success"></i></a>
                        </td>
                        <td>
                            <form
                                action="{{ route('covers.cover-items.destroy', ['cover' => $cover->number, 'coverItem' => $coverRow->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="coverItem-{{ $coverRow->id }}-{{ $cover->number }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить изделие с чертежом {{ $coverRow->drawing }} из покрытия с номером {{ $cover->number }}?">
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
