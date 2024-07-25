@extends('layouts.main-for-modal')
@section('content')

    @include('includes.filters.route.route-choice')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Номер</th>
        </tr>
        </thead>
        <tbody>
        @foreach($routes as $route)
            <tr>
                <td>{{ $route->number }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $routes->withQueryString()->links() }}
    </div>
@endsection
