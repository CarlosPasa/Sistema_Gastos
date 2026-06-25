<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de gastos</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .date {
            text-align: center;
            color: #6b7280;
            font-size: 11px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2563eb;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #e5e7eb;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center; color:#1e3a8a; margin-bottom:5px;">
        REPORTE DE GASTOS
    </h1>

    <p style="text-align:center; color:#6b7280; margin-bottom:20px;">
        Generado: {{ now()->format('d/m/Y H:i') }}
    </p>
    @if(
        request('search') ||
        request('category_id') ||
        request('date_from') ||
        request('date_to')
    )
        <div style="margin-bottom: 15px; font-size: 11px; color: #666;">
            <strong>Filtros aplicados:</strong><br>

            @if(request('search'))
                Búsqueda: {{ request('search') }}<br>
            @endif

            @if(request('category_id'))
                Categoría:
                {{ \App\Models\Category::find(request('category_id'))?->name }}<br>
            @endif

            @if(request('date_from'))
                Desde: {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }}<br>
            @endif

            @if(request('date_to'))
                Hasta: {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}<br>
            @endif
        </div>
    @endif

    <div style="
        margin-bottom:20px;
        padding:10px;
        background:#f3f4f6;
        border:1px solid #d1d5db;
    ">
        <strong>Total registros:</strong> {{ $expenses->count() }}
        <br>
        <strong>Total:</strong>
        S/ {{ number_format($expenses->sum('amount'), 2) }}
    </div>
    <table>
        <thead>
            <tr>
                <th width="15%">Fecha</th>
                <th width="20%">Categoría</th>
                <th width="45%">Descripción</th>
                <th class="right" width="20%">Monto</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                    <td>{{ $expense->category->name }}</td>
                    <td>{{ $expense->description }}</td>
                    <td class="right">S/ {{ number_format($expense->amount, 2) }}</td>
                </tr>
            @endforeach

            <tr class="total">
                <td colspan="3" class="right">TOTAL GENERAL</td>
                <td class="right">
                    S/ {{ number_format($expenses->sum('amount'), 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
