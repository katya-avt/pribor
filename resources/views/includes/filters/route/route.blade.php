<form
    action="{{ route('routes.index') }}"
    method="get">

    <x-forms.inputs.input
        name="search"
        label="Поиск:"
        type="text"
        message={{ $message }}
            value="{{ $data['search'] ?? '' }}">
    </x-forms.inputs.input>

    <div class="input-group">
        <x-forms.inputs.input
            name="point_code"
            label="Точка маршрута:"
            type="text"
            message={{ $message }}
                value="{{ $data['point_code'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="point" size="lg" title="Выберете точку маршрута"></x-modals.modal>
    </div>

    <div class="input-group">
        <x-forms.inputs.input
            name="operation_code"
            label="Операция:"
            type="text"
            message={{ $message }}
                value="{{ $data['operation_code'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="operation" size="lg" title="Выберете операцию"></x-modals.modal>
    </div>

    <div class="d-flex mb-3">
        <div class="mr-5">
            <x-forms.buttons.button label="Применить"></x-forms.buttons.button>
        </div>
        <x-forms.buttons.filter-clean route="routes.index"></x-forms.buttons.filter-clean>
    </div>
</form>
