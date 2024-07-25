<thead>
<tr>
    <th style="font-weight: bold">Спецификация покрытия:</th>
    <td>{{ $cover->number }}</td>
</tr>
</thead>

<thead>
<tr>
    <th style="font-weight: bold">Действительна на</th>
    <td>{{ date('d.m.Y H:i:s') }}</td>
</tr>
</thead>

<br>

<table>
    <thead>
    <tr>
        <th style="font-weight: bold; border: solid; width: 220px;">Г</th>
        <th style="font-weight: bold; border: solid; width: 220px;">Чертеж</th>
        <th style="font-weight: bold; border: solid; width: 220px;">Наименование</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Площадь</th>
        <th style="font-weight: bold; border: solid; width: 100px;">Потребление</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Ед.Изм.</th>
    </tr>
    </thead>
    <tbody>
    @foreach($coverData as $coverRow)
        <tr>
            <td style="border: solid; width: 220px;">{{ $coverRow->group->name }}</td>
            <td style="border: solid; width: 220px;">{{ $coverRow->drawing }}</td>
            <td style="border: solid; width: 220px;">{{ $coverRow->name }}</td>
            <td style="border: solid; width: 70px;">{{ $coverRow->pivot->area }}</td>
            <td style="border: solid; width: 100px;">{{ $coverRow->pivot->consumption }}</td>
            <td style="border: solid; width: 70px;">{{ $coverRow->unit->short_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
