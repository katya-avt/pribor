@if($descendant->operations->isNotEmpty())
    <tr class="d-none {{ $operation->code }}">
        <th scope="col" class="align-middle">
            <button class="operation-details-btn btn" data-id="{{ $descendant->code }}">+
            </button>{{ $descendant->code }}</th>
        <th scope="col" class="align-middle">{{ $descendant->name }}</th>
    </tr>
@else
    <tr class="d-none {{ $operation->code }} selectable">
        <td class="selectable-attribute align-middle">{{ $descendant->code }}</td>
        <td class="align-middle">{{ $descendant->name }}</td>
    </tr>
@endif

@if($descendant->operations->isNotEmpty())
    @foreach($descendant->operations as $nextDescendant)
        @include('choice-modals.range.operation.descendant', ['operation'=> $descendant, 'descendant' => $nextDescendant])
    @endforeach
@endif
