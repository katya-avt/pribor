@extends('layouts.main')
@section('content')
    <form
        action="{{ route('routes.route-points.rearrange.update', ['route' => $route->number]) }}"
        method="post">
        @csrf
        @method('PATCH')

        <x-detailed-information.key-value
            key="Текущая расцеховка:"
            value="{{ $pointsOrder->values()->implode(' - ') }}">
        </x-detailed-information.key-value>

        @foreach($pointsOrder as $order => $point)
            <div class="row g-3 align-items-center mb-3">
                <div class="col-auto">
                    <label for="order[{{ $order }}]" class="col-form-label">{{ $point }}</label>
                </div>
                <div class="col-auto">
                    <select
                        name="order[{{ $order }}]"
                        class="form-select @error('order.' . $order) is-invalid @enderror"
                        aria-describedby="order[{{ $order }}]">
                        @for($i = 1; $i <= $pointCount; $i++)
                            <option
                                value="{{ $i }}"
                                {{ $order == $i ? 'selected' : '' }}>
                                {{ $i }}</option>
                        @endfor
                    </select>

                    @error('order.' . $order)
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endforeach

        <x-forms.buttons.button label="Изменить"></x-forms.buttons.button>
    </form>
@endsection
