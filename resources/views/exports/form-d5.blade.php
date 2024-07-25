<thead>
<tr>
    <th style="font-weight: bold">Форма Д5:</th>
    <td>{{ $item->drawing }} ({{ $item->name }})</td>
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
        <th style="font-weight: bold; border: solid; width: 220px;">Чертеж</th>
        <th style="font-weight: bold; border: solid; width: 220px;">Наименование</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Кол-во</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Ед.Изм.</th>
        <th style="font-weight: bold; border: solid; width: 100px;">Номер спец.</th>
    </tr>
    </thead>
    <tbody>
    @foreach($itemsThatContainItem as $itemThatContainItem)
        <tr>
            <td style="border: solid; width: 220px;">{{ $itemThatContainItem->drawing }}</td>
            <td style="border: solid; width: 220px;">{{ $itemThatContainItem->name }}</td>
            <td style="border: solid; width: 70px;">{{ $itemThatContainItem->cnt }}</td>
            <td style="border: solid; width: 70px;">{{ $item->unit->short_name }}</td>
            <td style="border: solid; width: 100px;">{{ $itemThatContainItem->number }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
