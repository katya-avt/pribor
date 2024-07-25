@extends('layouts.main')
@section('content')
    <p>Если вы удаляете изделие <b>{{ $item->drawing }}</b>, то <b>все спецификации</b>, которые его содержат,
        становятся <b>недействительными</b>.</p>

    <ol class="list-group list-group-numbered mb-3">
        @foreach($item->relatedSpecifications as $relatedSpecification)
            <li class="list-group-item"><a
                    href="{{ route('specifications.show', ['specification' => $relatedSpecification->number]) }}">{{ $relatedSpecification->number }}</a>
            </li>
        @endforeach
    </ol>

    <p>В связи с этим укажите изделие, которое <b>заменит</b> удаляемое во всех этих спецификациях.</p>

    <form action="{{ route('items.component-replacement.store', $item->id) }}" method="post">
        @csrf
        <div class="input-group mb-3">
            <x-forms.inputs.input
                name="drawing"
                label="Чертеж:"
                type="text"
                message={{ $message }}>
            </x-forms.inputs.input>

            <x-modals.modal name="item" size="lg" title="Выберете изделие" type="choose"></x-modals.modal>
        </div>

        <p>Укажите коэффициент - число, отражающее,
            сколько этого нового изделия необходимо, чтобы заменить одно удаляемое изделие.
            Например, если одно удаляемое изделие заменит одно новое изделие, то коэффициент 1.</p>

        <x-forms.inputs.input
            name="factor"
            label="Коэффициент:"
            type="text"
            message={{ $message }}>
        </x-forms.inputs.input>

        <div class="mt-3">
            <x-forms.buttons.button label="Заменить"></x-forms.buttons.button>
        </div>
    </form>
@endsection
