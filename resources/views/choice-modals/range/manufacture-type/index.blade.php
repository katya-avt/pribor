@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Наименование</th>
            </tr>
        </thead>
        <tbody>
            @foreach($manufactureTypes as $manufactureType)
                <tr>
                    <td>{{ $manufactureType->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
