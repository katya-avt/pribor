@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Наименование</th>
                <th scope="col">Полное наименование</th>
            </tr>
        </thead>
        <tbody>
            @foreach($units as $unit)
                <tr>
                    <td>{{ $unit->short_name }}</td>
                    <td>{{ $unit->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
