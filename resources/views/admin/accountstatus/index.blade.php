@extends('adminlte::page')

@section('title', 'Estado de cuenta')


@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h4>Estado de cuenta</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div>
                    <a href="{{ route('admin.accountstatus.index') }}" class="btn btn-dark">
                        <i class="fas fa-list"></i> Todos</a>
                    <a href="{{ route('admin.accountstatus.index', ['status' => 'suficiente']) }}" class="btn btn-success">
                        <i class="fas fa-check"> </i> Suficiente</a>
                    <a href="{{ route('admin.accountstatus.index', ['status' => 'pendiente']) }}" class="btn btn-danger">
                        <i class="fas fa-exclamation"></i> Pendiente</a>
                    <a href="{{ route('admin.accountstatus.index', ['status' => 'agotándose']) }}" class="btn btn-warning">
                        <i class="fas fa-exclamation-triangle"></i> Agotándose</a>
                </div>
            </div>

            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRES</th>
                        <th>FECHA DE ÚLTIMO PAGO</th>
                        <th>MÉTODO DE PAGO</th>
                        <th>SALDO ACTUAL S/.</th>
                        <th>ESTADO</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($accountstatus as $accstatus)
                        <tr>
                            <td>{{ $accstatus->id }}</td>
                            <td>{{ $accstatus->names }}</td>
                            <td>{{ $accstatus->formatted_date }}</td>
                            <td>{{ $accstatus->metodo }}</td>
                            <td>{{ $accstatus->saldo }}</td>
                            <td>{{ $accstatus->status }}</td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <div class="card-footer">

        </div>
    </div>

@stop
@section('js')
    <script>
        $('#datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            }
        });
    </script>

@stop
