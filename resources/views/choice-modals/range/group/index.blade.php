@extends('layouts.main-for-modal')
@section('content')
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Наименование</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
            @if($group->descendants->isNotEmpty())
                <tr>
                    <th scope="col" class="align-middle">
                        <button class="group-details-btn btn" data-id="{{ $group->id }}">+
                        </button>{{ $group->name }}</th>
                </tr>
            @else
                <tr class="selectable align-middle">
                    <th scope="col" class="selectable-attribute">{{ $group->name }}</th>
                </tr>
            @endif
            @foreach($group->descendants as $descendant)
                @include('choice-modals.range.group.descendant', ['group'=> $group, 'descendant' => $descendant])
            @endforeach
        @endforeach
        </tbody>
    </table>
@endsection
