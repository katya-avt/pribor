@if($descendant->groups->isNotEmpty())
    <tr class="d-none {{ $group->id }}">
        <th scope="col" class="align-middle">
            <button class="group-details-btn btn" data-id="{{ $descendant->id }}">+
            </button>{{ $descendant->name }}</th>
    </tr>
@else
    <tr class="d-none {{ $group->id }} selectable">
        <td class="selectable-attribute align-middle">{{ $descendant->name }}</td>
    </tr>
@endif

@if($descendant->groups->isNotEmpty())
    @foreach($descendant->groups as $nextDescendant)
        @include('choice-modals.range.group.descendant', ['group'=> $descendant, 'descendant' => $nextDescendant])
    @endforeach
@endif
