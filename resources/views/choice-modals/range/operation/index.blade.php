@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Код</th>
            <th scope="col">Наименование операции</th>
        </tr>
        </thead>
        <tbody>
        @foreach($operations as $operation)
            @if($operation->descendants->isNotEmpty())
                <tr>
                    <th scope="col" class="align-middle">
                        <button class="operation-details-btn btn" data-id="{{ $operation->code }}">+
                        </button>{{ $operation->code }}</th>
                    <th scope="col" class="align-middle">{{ $operation->name }}</th>
                </tr>
            @else
                <tr class="selectable">
                    <th scope="col" class="selectable-attribute align-middle">{{ $operation->code }}</th>
                    <th scope="col" class="align-middle">{{ $operation->name }}</th>
                </tr>
            @endif
            @foreach($operation->descendants as $descendant)
                @include('choice-modals.range.operation.descendant', ['operation'=> $operation, 'descendant' => $descendant])
            @endforeach
        @endforeach
        </tbody>
    </table>
@endsection
