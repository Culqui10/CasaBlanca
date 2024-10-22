@extends('adminlte::page')
@section('title', 'Productos')
@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <!--<a href="{{ route('admin.productos.create') }}" class="btn btn-success float-right"><i class="fas fa-plus-circle"></i> Registrar</a>-->
            <button class="btn btn-success float-right" id="btnNuevo"><i class="fas fa-plus-circle"></i> Registrar</button>
            <h4>Listado de Productos</h4>
        </div>
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>IMAGEN</th>
                        <th>PRODUCTO</th>
                        <th>PRECIO</th>
                        <th>CATEGORIA</th>
                        <th>DESCRIPCION</th>
                        <th width=20></th>
                        <th width=20></th>
                        <th width=20></th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            
                            <td>
                                <img src="{{ asset($producto->url_foto) }}" width="50" alt="Foto">
                            </td>
                                            
                            <td>{{ $producto->name }}</td>
                            <td>{{ $producto->price }}</td>
                            <td>{{ $producto->categorianame }}</td>
                            <td>{{ $producto->description }}</td>

                            <<td>
                                <button class="btnVerFotos btn btn-primary" id="{{ $producto->id }}">
                                    <i class="fa fa-eye"></i>
                                </button>
                                </td>

                                <td><!--<a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>-->
                                    <button class="btnEditar btn btn-primary" id={{ $producto->id }}><i
                                            class="fa fa-edit"></i></button>
                                </td>
                                <td>
                                    <form action="{{ route('admin.productos.destroy', $producto->id) }}" method="POST"
                                        class="fmrEliminar">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                                </form>

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
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Productos</h5>
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

    <div class="modal fade" id="modalfotos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Ver Fotos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contenidofotos">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    @livewireScripts
    @vite(['resources/js/app.js'])
    <script>
        $('#datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });

        $('#btnNuevo').click(function() {
            $.ajax({
                url: "{{ route('admin.productos.create') }}",
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
                url: "{{ route('admin.productos.edit', '_id') }}".replace('_id', id),
                type: "GET",
                success: function(response) {
                    $('#formModal .modal-body').html(response);
                    $('#formModal').modal('show');
                }
            });
        });
        $(".btnVerFotos").click(function() {
            id = $(this).attr('id');
            // console.log(id)
            $.ajax({
                url: "{{ route('admin.foto.show', '_id') }}".replace('_id', id),
                type: "GET",
                success: function(response) {
                    $("#contenidofotos").html(response);
                    $("#modalfotos").modal('show');
                }
            });
        });


        $(".frmPerfilFoto").submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "¿Estás seguro de seleccionarlo?",
                text: "Se cambiará a foto de perfil",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        $(".frmEliminarFoto").submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "¿Estás seguro de eliminar?",
                text: "Esta acción es irreversible",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
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
                title: "Mensaje",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif

@stop
