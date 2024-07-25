<form
    action="{{ route('periodic-requisites.purchase-price.show', ['item' => $item->id]) }}"
    method="get">

    <x-forms.selects.select-array
        name="period"
        label="С начала"
        :options="$periods"
        message={{ $message }}
            oldValue="{{ $data['period'] ?? '' }}">
    </x-forms.selects.select-array>

    <div class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <label for="quarter" class="col-form-label">Квартал:</label>
        </div>
        <div class="col-auto">
            <input class="form-control" name="quarter" type="month" step="3" value="{{ $data['quarter'] ?? '' }}">
        </div>
    </div>

    <x-forms.inputs.input
        name="month"
        label="Месяц:"
        type="month"
        message={{ $message }}
            value="{{ $data['month'] ?? '' }}">
    </x-forms.inputs.input>

    <x-forms.inputs.input
        name="date"
        label="День:"
        type="date"
        message={{ $message }}
            value="{{ $data['date'] ?? '' }}">
    </x-forms.inputs.input>

    <x-forms.inputs.input
        name="from_date"
        label="Интервал с:"
        type="date"
        message={{ $message }}
            value="{{ $data['from_date'] ?? '' }}">
    </x-forms.inputs.input>

    <x-forms.inputs.input
        name="to_date"
        label="по:"
        type="date"
        message={{ $message }}
            value="{{ $data['to_date'] ?? '' }}">
    </x-forms.inputs.input>

    <div class="d-flex mb-3">
        <div class="mr-5">
            <x-forms.buttons.button label="Применить"></x-forms.buttons.button>
        </div>
        <x-forms.buttons.filter-clean route="periodic-requisites.purchase-price.show"
                                      :params="['item' => $item->id]"></x-forms.buttons.filter-clean>
    </div>
</form>
