@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Номер</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itemSpecifications as $itemSpecification)
                <tr>
                    <td>{{ $itemSpecification->number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
