<form
      action="{{ route('periodic-requisites.labor-payment.index') }}"
      method="get">

    <x-forms.inputs.input
        name="search"
        label="Поиск:"
        type="text"
        message={{ $message }}
            value="{{ $data['search'] ?? '' }}">
    </x-forms.inputs.input>

    <div class="d-flex mb-3">
        <div class="mr-5">
            <x-forms.buttons.button label="Применить"></x-forms.buttons.button>
        </div>
        <x-forms.buttons.filter-clean route="periodic-requisites.labor-payment.index"></x-forms.buttons.filter-clean>
    </div>
</form>
