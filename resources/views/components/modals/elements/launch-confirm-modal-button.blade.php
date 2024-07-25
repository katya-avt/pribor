<div>
    <button type="button" class="border-0 bg-transparent"
            data-bs-toggle="modal"
            data-bs-target="#{{ $name }}Modal">
        @if($confirmType == 'delete')
            <i class="fas fa-trash text-danger" role="button"></i>
        @endif
        @if($confirmType == 'restore')
            <i class="fas fa-trash-restore text-success" role="button"></i>
        @endif
    </button>
</div>
