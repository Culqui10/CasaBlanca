@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>GESTIÓN DE PENSIONISTAS - CASA BLANCA</h1>
@stop


@section('content')
    <div class="row">
        <!-- Estadísticas principales -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalPensionistas }}</h3>
                    <p>Total de Pensionistas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.pensioners.index') }}" class="small-box-footer">Más información <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $pagosSuficientes }}</h3>
                    <p>Pagos Suficiente</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('admin.accountstatus.index', ['estado' => 'suficiente']) }}" class="small-box-footer">Más
                    información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $pagosPendientes }}</h3>
                    <p>Pagos Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <a href="{{ route('admin.accountstatus.index', ['estado' => 'pendiente']) }}" class="small-box-footer">Más
                    información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pagosAgotandose }}</h3>
                    <p>Pagos Agotándose</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('admin.accountstatus.index', ['estado' => 'agotandose']) }}" class="small-box-footer">Más
                    información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>

    <!-- Atajos -->
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('admin.consumptions.index') }}" class="btn btn-primary btn-block">
                <i class="fas fa-utensils"></i> Consumo Diario
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.pensioners.index') }}" class="btn btn-success btn-block">
                <i class="fas fa-user-plus"></i> Pensionista
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-warning btn-block">
                <i class="fas fa-dollar-sign"></i> Pago
            </a>
        </div>
    </div>
    <br>
    <!-- Gráficos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Cantidad de pensionistas</h3>
                    <form method="GET" id="graficoPensionistasForm" class="form-inline">
                        <label for="anio" class="mr-2">Seleccione el año:</label>
                        <select name="anio" id="anio" class="form-control" onchange="this.form.submit()">
                            @foreach ($anios as $anio)
                                <option value="{{ $anio }}" {{ $anio == $anioSeleccionado ? 'selected' : '' }}>
                                    {{ $anio }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="graficoPensionistas"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Menús más consumidos</h3>
                    <form method="GET" id="graficoMenusForm" class="form-inline">
                        <label for="tipoComida" class="mr-2">Tipo de comida:</label>
                        <select name="tipoComida" id="tipoComida" class="form-control" onchange="this.form.submit()">
                            <option value="todos" {{ $tipoComidaSeleccionado == 'todos' ? 'selected' : '' }}>Todos
                            </option>
                            @foreach ($tiposDeComida as $tipo)
                                <option value="{{ $tipo }}"
                                    {{ $tipoComidaSeleccionado == $tipo ? 'selected' : '' }}>
                                    {{ ucfirst($tipo) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="graficoMenus"></canvas>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos para los gráficos
        const datosPensionistas = @json($datosPensionistas);
        const datosMenus = @json($datosMenus);

        // Gráfico de Barras: Pensionistas
        const ctxPensionistas = document.getElementById('graficoPensionistas').getContext('2d');
        new Chart(ctxPensionistas, {
            type: 'bar',
            data: datosPensionistas,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de Barras: Menús
        const ctxMenus = document.getElementById('graficoMenus').getContext('2d');
        new Chart(ctxMenus, {
            type: 'bar',
            data: datosMenus,
            options: {
                responsive: true,
            }
        });

        function actualizarGraficoPensionistas() {
            const anio = document.getElementById('anio').value;

            fetch(`/admin/graficos/pensioners?anio=${anio}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('graficoPensionistas').getContext('2d');

                    // Actualizar el filtro de años dinámicamente
                    const anioSelect = document.getElementById('anio');
                    anioSelect.innerHTML = ''; // Limpiar las opciones existentes
                    data.anios.forEach(a => {
                        const option = document.createElement('option');
                        option.value = a;
                        option.text = a;
                        if (a == anio) {
                            option.selected = true;
                        }
                        anioSelect.appendChild(option);
                    });

                    // Si existe un gráfico, destruirlo antes de crear uno nuevo
                    if (window.pensionistasChart) {
                        window.pensionistasChart.destroy();
                    }

                    // Crear el gráfico con los datos obtenidos
                    window.pensionistasChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.meses, // Meses
                            datasets: [{
                                label: 'Cantidad de Pensionistas',
                                data: data.totales, // Totales por mes
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        }


        function actualizarGraficoMenus() {
            const tipoComida = document.getElementById('tipoComida').value; // Obtener el valor del filtro

            // Hacer una solicitud al servidor con el tipo de comida seleccionado
            fetch(`/admin/graficos/menus?tipoComida=${tipoComida}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('graficoMenus').getContext('2d');

                    // Si existe un gráfico, destruirlo antes de crear uno nuevo
                    if (window.menusChart) {
                        window.menusChart.destroy();
                    }

                    // Crear el gráfico con los datos obtenidos
                    window.menusChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels, // Nombres de los menús
                            datasets: [{
                                label: 'Menús Más Consumidos',
                                data: data.totales, // Totales por menú
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error:', error)); // Manejar errores en la solicitud
        }


        document.addEventListener('DOMContentLoaded', function() {
            actualizarGraficoPensionistas();
            actualizarGraficoMenus();
        });
    </script>
@stop
