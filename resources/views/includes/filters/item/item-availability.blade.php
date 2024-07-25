<form id="itemFilterForm"
      action="{{ route('item-availability-and-consumption.availability.index') }}"
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
            name="group_id"
            label="Группа:"
            type="text"
            message={{ $message }}
                value="{{ $data['group_id'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="group" size="lg" title="Выберете группу"></x-modals.modal>
    </div>

    <div class="input-group">
        <x-forms.inputs.input
            name="item_type_id"
            label="Тип номенклатуры:"
            type="text"
            message={{ $message }}
                value="{{ $data['item_type_id'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="itemType" size="lg" title="Выберете тип номенклатуры"></x-modals.modal>
    </div>

    <div class="input-group">
        <x-forms.inputs.input
            name="main_warehouse_code"
            label="Основной склад:"
            type="text"
            message={{ $message }}
                value="{{ $data['main_warehouse_code'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="mainWarehouse" size="lg" title="Выберете основной склад"></x-modals.modal>
    </div>

    <div class="input-group">
        <x-forms.inputs.input
            name="manufacture_type_id"
            label="Тип изготовления:"
            type="text"
            message={{ $message }}
                value="{{ $data['manufacture_type_id'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="manufactureType" size="lg" title="Выберете тип изготовления"></x-modals.modal>
    </div>

    <div class="d-flex mb-3">
        <div class="mr-5">
            <x-forms.buttons.button label="Применить"></x-forms.buttons.button>
        </div>
        <x-forms.buttons.filter-clean
            route="item-availability-and-consumption.availability.index"></x-forms.buttons.filter-clean>
    </div>
</form>
