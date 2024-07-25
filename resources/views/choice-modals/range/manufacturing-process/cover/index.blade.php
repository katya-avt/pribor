@extends('layouts.main-for-modal')
@section('content')

    @include('includes.filters.cover.cover-choice')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Номер</th>
        </tr>
        </thead>
        <tbody>
        @foreach($covers as $cover)
            <tr>
                <td>{{ $cover->number }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $covers->withQueryString()->links() }}
    </div>
@endsection
