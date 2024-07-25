<div>
    <div class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <label for="{{ $name }}" class="col-form-label">{{ $label }}</label>
        </div>
        <div class="col-auto">
            <select
                id="{{ $name }}"
                name="{{ $name }}"
                class="form-select  @error($name) is-invalid @enderror"
                aria-describedby="{{ $name }}">
                <option value="">--{{ $label }}--</option>
                @foreach($options as $key => $value)
                    <option
                        value="{{ $key }}"
                        {{ ($oldValue ? $oldValue == $key : old($name) == $key) ? 'selected' : '' }}>
                        {{ $value }}</option>
                @endforeach
            </select>

            @error($name)
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
