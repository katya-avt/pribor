@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Наименование</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mainWarehouses as $mainWarehouse)
                <tr>
                    <td>{{ $mainWarehouse->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
