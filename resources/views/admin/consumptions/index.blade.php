@extends('adminlte::page')

@section('title', 'Consumo diario')


@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success float-right" id="btnNuevo"><i class="fas fa-plus-circle"></i> Registrar</button>
            <h4>Consumos diarios</h4>
        </div>
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>PENSIONISTA</th>
                        <th>FECHA</th>
                        <th>DESAYUNO</th>
                        <th>ALMUERZO</th>
                        <th>CENA</th>
                        <th>TOTAL S/.</th>
                        <th width=20></th>
                        <th width=20></th>

                    </tr>

                </thead>
                <tbody>

                    @foreach ($consumptions as $cons)
                        <tr>
                            <td>{{ $cons->id }}</td>
                            <td>{{ $cons->names }}</td>
                            <td>{{ $cons->formatted_date }}</td>
                            <td>
                                @if ($cons->desayuno === 'Sí')
                                    <a href="#" class="text-primary" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-html="true" 
                                       title="
                                            <strong>Menú:</strong> {{ $cons->desayuno_details->menu_name ?? 'No disponible' }}<br>
                                            <strong>Precio:</strong> S/. {{ $cons->desayuno_details->price ?? 0 }}<br>
                                            <strong>Adicional:</strong> {{ $cons->desayuno_details->adicional ?? 'Ninguno' }}<br>
                                            <strong>Costo adicional:</strong> S/. {{ $cons->desayuno_details->aditional_cost ?? 0 }}<br>
                                            <strong>Total:</strong> S/. {{ $cons->desayuno_details->total ?? 0 }}
                                       ">
                                        Sí
                                    </a>
                                @else
                                    No
                                @endif
                            </td>
                            <td>
                                @if ($cons->almuerzo === 'Sí')
                                    <a href="#" class="text-primary" data-bs-toggle="tooltip" data-bs-html="true"
                                        title="<strong>Menú:</strong> {{ $cons->almuerzo_details->menu_name ?? 'No disponible' }}<br>
                                              <strong>Precio:</strong> S/. {{ $cons->almuerzo_details->price ?? 0 }}<br>
                                              <strong>Adicional:</strong> {{ $cons->almuerzo_details->adicional ?? 'Ninguno' }}<br>
                                              <strong>Costo adicional:</strong> S/. {{ $cons->almuerzo_details->aditional_cost ?? 0 }}<br>
                                              <strong>Total:</strong> S/. {{ $cons->almuerzo_details->total ?? 0 }}">
                                        Sí
                                    </a>
                                @else
                                    No
                                @endif
                            </td>
                            <td>
                                @if ($cons->cena === 'Sí')
                                    <a href="#" class="text-primary" data-bs-toggle="tooltip" data-bs-html="true"
                                        title="<strong>Menú:</strong> {{ $cons->cena_details->menu_name ?? 'No disponible' }}<br>
                                              <strong>Precio:</strong> S/. {{ $cons->cena_details->price ?? 0 }}<br>
                                              <strong>Adicional:</strong> {{ $cons->cena_details->adicional ?? 'Ninguno' }}<br>
                                              <strong>Costo adicional:</strong> S/. {{ $cons->cena_details->aditional_cost ?? 0 }}<br>
                                              <strong>Total:</strong> S/. {{ $cons->cena_details->total ?? 0 }}">
                                        Sí
                                    </a>
                                @else
                                    No
                                @endif
                            </td>
                            <td>{{ $cons->total }}</td>
                            <td>
                                <button class="btnEditar btn btn-primary" id="{{ $cons->id }}"><i
                                        class="fa fa-edit"></i></button>
                            </td>
                            <td>
                                <form action="{{ route('admin.consumptions.destroy', $cons->id) }}" method="POST"
                                    class="fmrEliminar">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <div class="card-footer">

        </div>
    </div>

    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Consumo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                </div>
            </div>
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

        $('#btnNuevo').click(function() {
            $.ajax({
                url: "{{ route('admin.consumptions.create') }}",
                type: "GET",
                success: function(response) {
                    $('#formModal .modal-body').html(response);
                    $('#formModal').modal('show');
                }
            })

        })
        $(".btnEditar").click(function() {
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('admin.consumptions.edit', '_id') }}".replace('_id', id),
                type: "GET",
                success: function(response) {
                    $('#formModal .modal-body').html(response);
                    $('#formModal').modal('show');
                }
            });

        });

        $(".fmrEliminar").submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Seguro de eliminar?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
         // Inicializar tooltips con soporte para HTML
         $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip({
                html: true // Permitir contenido HTML en los tooltips
            });
        });
    </script>

    @if (session('success') !== null)
        <script>
            Swal.fire({
                title: "Proceso Exitoso",
                text: "{{ session('success') }}",
                icon: "success"
            });
        </script>
    @endif

    @if (session('error') !== null)
        <script>
            Swal.fire({
                title: "Error de proceso",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif
@stop

@section('css')
    <style>
        .tooltip-inner {
            max-width: 300px; /* Ajusta el ancho máximo del tooltip */
            background-color: #4c4c4c; /* Fondo blanco */
            color: #f7efef; /* Texto negro */
            border: 1px solid #353535; /* Borde gris claro */
            padding: 10px; /* Espaciado interno */
            font-size: 14px; /* Tamaño de fuente */
            text-align: left; /* Alineación del texto */
            border-radius: 5px; /* Esquinas redondeadas */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Sombra para dar profundidad */
        }
        .tooltip-arrow {
            color: #ffffff; /* Color de la flecha, debe coincidir con el fondo */
            border: 1px solid #cccccc; /* Borde de la flecha */
        }
        a[data-bs-toggle="tooltip"] {
            text-decoration: underline; /* Subrayado para enlaces con tooltip */
            cursor: pointer;
            color: #007bff; /* Azul estándar de Bootstrap */
        }

        a[data-bs-toggle="tooltip"]:hover {
            color: #0056b3; /* Azul más oscuro al pasar el mouse */
        }
    </style>
@stop

