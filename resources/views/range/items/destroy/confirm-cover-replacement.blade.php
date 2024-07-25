@extends('layouts.main')
@section('content')
    <p>Если вы удаляете покрытие <b>{{ $item->drawing }}</b>, то <b>все спецификации</b>, которые его содержат,
        становятся <b>недействительными</b>.</p>

    <ol class="list-group list-group-numbered mb-3">
        @foreach($item->relatedCovers as $relatedCover)
            <li class="list-group-item"><a
                    href="{{ route('covers.show', ['cover' => $relatedCover->number]) }}">{{ $relatedCover->number }}</a>
            </li>
        @endforeach
    </ol>

    <p>В связи с этим укажите покрытие, которое <b>заменит</b> удаляемое во всех этих спецификациях.</p>

    <form action="{{ route('items.cover-replacement.store', $item->id) }}" method="post">
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
            уровень потребления нового покрытия в литрах/см2 по сравнении с потреблением удаляемого покрытия.
            Например, если на 1 см2 потребляется одинаковое кол-во удаляемого и нового покрытия, то коэффициент
            1.</p>

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
