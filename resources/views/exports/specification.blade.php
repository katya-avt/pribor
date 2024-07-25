<thead>
<tr>
    <th style="font-weight: bold">Конструкторская спецификация:</th>
    <td>{{ $specification->number }}</td>
</tr>
</thead>

<thead>
<tr>
    <th style="font-weight: bold">Действительна на</th>
    <td>{{ date('d.m.Y H:i:s') }}</td>
</tr>
</thead>

<br>

<table style="border: solid">
    <thead>
    <tr>
        <th style="font-weight: bold; border: solid; width: 220px;">Г</th>
        <th style="font-weight: bold; border: solid; width: 220px;">Чертеж</th>
        <th style="font-weight: bold; border: solid; width: 220px;">Наименование</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Кол-во</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Ед.Изм.</th>
    </tr>
    </thead>
    <tbody>
    @foreach($specificationData as $specificationRow)
        <tr>
            <td style="border: solid; width: 220px;">{{ $specificationRow->group->name }}</td>
            <td style="border: solid; width: 220px;">{{ $specificationRow->drawing }}</td>
            <td style="border: solid; width: 220px;">{{ $specificationRow->name }}</td>
            <td style="border: solid; width: 70px;">{{ $specificationRow->pivot->cnt }}</td>
            <td style="border: solid; width: 70px;">{{ $specificationRow->unit->short_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
