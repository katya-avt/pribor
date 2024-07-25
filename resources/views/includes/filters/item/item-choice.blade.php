<form id="itemFilterForm"
      action="{{ route('item-choice') }}"
      method="get">

    <x-forms.inputs.input
        name="search"
        label="Поиск:"
        type="text"
        message={{ $message }}
            value="{{ $data['search'] ?? '' }}">
    </x-forms.inputs.input>

    <x-forms.selects.select
        name="group_id"
        label="Группа"
        :options="$groups"
        key="name"
        message={{ $message }}
            oldValue="{{ $data['group_id'] ?? '' }}">
    </x-forms.selects.select>

    <x-forms.selects.select
        name="item_type_id"
        label="Тип номенклатуры"
        :options="$itemTypes"
        key="name"
        message={{ $message }}
            oldValue="{{ $data['item_type_id'] ?? '' }}">
    </x-forms.selects.select>

    <x-forms.selects.select
        name="main_warehouse_code"
        label="Основной склад"
        :options="$mainWarehouses"
        key="name"
        message={{ $message }}
            oldValue="{{ $data['main_warehouse_code'] ?? '' }}">
    </x-forms.selects.select>

    <x-forms.selects.select
        name="manufacture_type_id"
        label="Тип изготовления"
        :options="$manufactureTypes"
        key="name"
        message={{ $message }}
            oldValue="{{ $data['manufacture_type_id'] ?? '' }}">
    </x-forms.selects.select>

    <div class="d-flex mb-3">
        <div class="mr-5">
            <x-forms.buttons.button label="Применить"></x-forms.buttons.button>
        </div>
        <x-forms.buttons.filter-clean route="item-choice"></x-forms.buttons.filter-clean>
    </div>
</form>
