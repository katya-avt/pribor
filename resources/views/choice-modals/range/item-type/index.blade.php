@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Наименование</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itemTypes as $itemType)
                <tr>
                    <td>{{ $itemType->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
