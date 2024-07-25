@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
        <div>
            <a href="{{ route('specifications.create') }}" class="btn btn-primary mb-3">Добавить спецификацию</a>
        </div>
    @endcan

    @include('includes.filters.specification.specification')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Номер</th>
            @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
                <th scope="col" colspan="2" class="col-1">Изменить</th>
            @endcan
        </tr>
        </thead>
        <tbody>
        @foreach($specifications as $specification)
            <tr>
                <td>
                    <a href="{{ route('specifications.show', ['specification' => $specification->number]) }}">{{ $specification->number }}</a>
                </td>
                @can(\App\Models\Users\Permission::SPECIFICATIONS_MANAGE)
                    @if(!$specification->added_to_order_at)
                        <td><a href="{{ route('specifications.edit', ['specification' => $specification->number]) }}"><i
                                    class="fas fa-edit text-success"></i></a>
                        </td>
                        <td>
                            <form
                                action="{{ route('specifications.destroy', ['specification' => $specification->number]) }}"
                                method="POST"
                                id="specificationNumber-{{ $specification->number }}-DeletionForm">
                                @csrf
                                @method('DELETE')
                                <x-modals.modal name="specificationNumber-{{ $specification->number }}" size="lg"
                                                title="Подтвердите удаление"
                                                type="confirm"
                                                body="Вы уверены, что хотите удалить спецификацию с номером {{ $specification->number }}?">
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
        {{ $specifications->withQueryString()->links() }}
    </div>
@endsection
