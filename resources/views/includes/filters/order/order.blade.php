<form
    action="{{ route('orders.index', $orderStatus) }}"
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
            name="customer_inn"
            label="Заказчик:"
            type="text"
            message={{ $message }}
                value="{{ $data['customer_inn'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="customer" size="lg" title="Выберете заказчика"></x-modals.modal>
    </div>

    <div class="input-group">
        <x-forms.inputs.input
            name="drawing"
            label="Чертеж:"
            type="text"
            message={{ $message }}
                value="{{ $data['drawing'] ?? '' }}">
        </x-forms.inputs.input>

        <x-modals.modal name="item" size="lg" title="Выберете изделие"></x-modals.modal>
    </div>

    <x-forms.selects.select-array
        name="sort_by"
        label="Сортировать по"
        :options="$columnsAndSortNameMatching"
        message={{ $message }}
            oldValue="{{ $data['sort_by'] ?? '' }}">
    </x-forms.selects.select-array>

    <x-forms.selects.select-array
        name="sort_direction"
        label="Порядок"
        :options="$sortDirectionAndSortNameMatching"
        message={{ $message }}
            oldValue="{{ $data['sort_direction'] ?? '' }}">
    </x-forms.selects.select-array>

    <div class="d-flex mb-3">
        <div class="mr-5">
            <x-forms.buttons.button label="Применить"></x-forms.buttons.button>
        </div>
        <x-forms.buttons.filter-clean route="orders.index" :params="[$orderStatus]"></x-forms.buttons.filter-clean>
    </div>
</form>
