@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
        @if(!$specification->added_to_order_at)
            @if(!($specification->relatedItems->isNotEmpty() && $specification->relatedItems()->first()->group->isDetail() && $specification->items()->count() == 1))
                <div>
                    <a href="{{ route('specifications.specification-items.create', ['specification' => $specification->number]) }}"
                       class="btn btn-primary mb-3">Добавить изделие</a>
                </div>
            @endif
        @endif
    @endcan

    <div class="mb-3">
        <a class="btn btn-primary" href="{{ route('specifications.export', ['specification' => $specification->number]) }}">
            Скачать</a>
    </div>

    <x-detailed-information.key-value
        key="Номер спецификации:"
        value="{{ $specification->number }}">
    </x-detailed-information.key-value>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Г</th>
            <th scope="col">Чертеж</th>
            <th scope="col">Наименование</th>
            <th scope="col">Кол-во</th>
            <th scope="col">Ед.изм.</th>
            @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
                @if(!$specification->added_to_order_at)
                    <th scope="col" colspan="2">Изменить</th>
                @endif
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($specificationData as $specificationRow)
            <tr>
                <td>{{ $specificationRow->group->name }}</td>
                <td><a href="{{ route('items.show', $specificationRow->id) }}">{{ $specificationRow->drawing }}</a></td>
                <td>{{ $specificationRow->name }}</td>
                <td>{{ $specificationRow->pivot->cnt }}</td>
                <td>{{ $specificationRow->unit->short_name }}</td>
                @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
                    @if(!$specification->added_to_order_at)
                        <td>
                            <a href="{{ route('specifications.specification-items.edit', ['specification' => $specification->number, 'specificationItem' => $specificationRow->id]) }}"><i
                                    class="fas fa-edit text-success"></i></a>
                        </td>
                        <td>
                            <form
                                action="{{ route('specifications.specification-items.destroy', ['specification' => $specification->number, 'specificationItem' => $specificationRow->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="specificationItem-{{ $specificationRow->id }}-{{ $specification->number }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    type="confirm"
                                    body="Вы уверены, что хотите удалить изделие с чертежом {{ $specificationRow->drawing }} из спецификации с номером {{ $specification->number }}?">
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
