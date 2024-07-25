@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Номер</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itemCovers as $itemCover)
                <tr>
                    <td>{{ $itemCover->number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
