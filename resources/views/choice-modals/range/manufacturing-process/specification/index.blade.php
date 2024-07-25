@extends('layouts.main-for-modal')
@section('content')

    @include('includes.filters.specification.specification-choice')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Номер</th>
        </tr>
        </thead>
        <tbody>
        @foreach($specifications as $specification)
            <tr>
                <td>{{ $specification->number }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $specifications->withQueryString()->links() }}
    </div>
@endsection
