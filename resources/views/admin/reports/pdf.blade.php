<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Consumos </title>
</head>
<body>
    <h1>Reporte de Consumos - {{ $pensioner->name }} {{ $pensioner->lastname }}</h1>
    <p>Mes: {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
    <p>Saldo Actual: S/. {{ number_format($pensioner->accountStatus->current_balance, 2) }}</p>
    <p>Estado: {{ ucfirst($pensioner->accountStatus->status) }}</p>

    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Desayuno</th>
                <th>Almuerzo</th>
                <th>Cena</th>
                <th>Total del Día</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{!! $row['details']['Desayuno'] !!}</td>
                    <td>{!! $row['details']['Almuerzo'] !!}</td>
                    <td>{!! $row['details']['Cena'] !!}</td>
                    <td>S/. {{ number_format($row['daily_total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 20px; font-weight: bold;">Total del Mes: S/. {{ number_format($monthlyTotal, 2) }}</p>
</body>
</html>
