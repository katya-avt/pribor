<thead>
<tr>
    <th></th>
    <th style="font-weight: bold">Технологический маршрут:</th>
    <td>{{ $route->number }}</td>
</tr>
</thead>

<thead>
<tr>
    <th></th>
    <th style="font-weight: bold">Действителен на</th>
    <td>{{ date('d.m.Y H:i:s') }}</td>
</tr>
</thead>

<thead>
<tr>
    <th></th>
    <th style="font-weight: bold">Расцеховка:</th>
    <td>{{ $routeData['points']->pluck('code')->implode(' - ') }}</td>
</tr>
</thead>

<br>

<table>
    <thead>
    <tr>
        <th style="font-weight: bold; border: solid; width: 40px;">N</th>
        <th style="font-weight: bold; border: solid; width: 220px;">Точка маршрута</th>
        <th style="font-weight: bold; border: solid; width: 220px;">Операция</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Т_Шт</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Т_Пов</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Т_ПЗ</th>
        <th style="font-weight: bold; border: solid; width: 70px;">Ставка</th>
    </tr>
    </thead>
    <tbody>
    @foreach($routeData['points']->zip($routeData['operations']) as [$point, $operation])
        <tr>
            <td style="border: solid; width: 40px;">{{ $point->pivot->point_number }}</td>
            <td style="border: solid; width: 220px;">{{ $point->code }} - {{ $point->name }}</td>
            <td style="border: solid; width: 220px;">{{ $operation->code }} - {{ $operation->name }}</td>
            <td style="border: solid; width: 70px;">{{ $operation->pivot->unit_time }}</td>
            <td style="border: solid; width: 70px;">{{ $operation->pivot->working_time }}</td>
            <td style="border: solid; width: 70px;">{{ $operation->pivot->lead_time }}</td>
            <td style="border: solid; width: 70px;">{{ $operation->pivot->rate_code }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
