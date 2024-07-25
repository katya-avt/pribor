@extends('layouts.main')
@section('content')

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @include('includes.filters.point')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Код</th>
            <th scope="col">Наименование</th>
            <th scope="col" colspan="2">Базовая ставка</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points as $point)
            <tr>
                <td><a href="{{ route('periodic-requisites.labor-payment.show', $point->code) }}">{{ $point->code }}</a></td>
                <td>{{ $point->name }}</td>
                <td>{{ $point->base_payment }}</td>
                @can(\App\Models\Users\Permission::PERIODIC_REQUISITES_MANAGE)
                    <td>
                        <x-modals.modal
                            name="laborPayment"
                            size="lg"
                            title="Изменение базовой ставки"
                            change="{{ $point->code }}">
                        </x-modals.modal>
                    </td>
                @endcan
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $points->withQueryString()->links() }}
    </div>
@endsection
