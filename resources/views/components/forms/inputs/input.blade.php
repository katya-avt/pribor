<div>
    <div class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <label for="{{ $name }}" class="col-form-label">{{ $label }}</label>
        </div>
        <div class="col-auto">
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                value="{{ $value ?? ($nestedArrayValue ? old($nestedArrayValue) : old($name)) }}"
                id="{{ $nestedArrayValue ?? $name }}"
                class="form-control @error($nestedArrayValue ?? $name) is-invalid @enderror"
                aria-describedby="{{ $nestedArrayValue ?? $name }}">

            @if($ajax)
                <div class="alert alert-danger d-none" id="error-alert"></div>
            @else
                @error($nestedArrayValue ?? $name)
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            @endif
        </div>
    </div>
</div>
