@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
        <div>
            <a href="{{ route('items.specifications.create', ['item' => $item->id]) }}" class="btn btn-primary mb-3">Добавить
                спецификацию</a>
        </div>
    @endcan

    <x-detailed-information.key-value
        key="Чертеж:"
        value="{{ $item->drawing }}">
    </x-detailed-information.key-value>

    <x-detailed-information.key-value
        key="Наименование:"
        value="{{ $item->name }}">
    </x-detailed-information.key-value>

    <table class="table table-bordered" id="specificationTable">
        <thead>
        <tr>
            <th scope="col">Номер</th>
            @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
                <th scope="col" class="col-1">Удалить</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($itemSpecifications as $itemSpecification)
            <tr>
                <td>
                    <a href="{{ route('specifications.show', ['specification' => $itemSpecification->number]) }}">{{ $itemSpecification->number }}</a>
                </td>
                @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
                    @if(!$itemSpecification->added_to_order_at)
                        <td>
                            <form
                                action="{{ route('items.specifications.destroy', ['item' => $item->id, 'specification' => $itemSpecification->number]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal
                                    name="itemSpecification-{{ $itemSpecification->number }}-{{ $item->id }}"
                                    size="lg"
                                    title="Подтвердите удаление"
                                    body="Вы уверены, что хотите удалить спецификацию с номером {{ $itemSpecification->number }} у изделия с чертежом {{ $item->drawing }}?">
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
