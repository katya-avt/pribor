@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Код</th>
                <th scope="col">Наименование</th>
            </tr>
        </thead>
        <tbody>
            @foreach($points as $point)
                <tr>
                    <td>{{ $point->code }}</td>
                    <td>{{ $point->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
