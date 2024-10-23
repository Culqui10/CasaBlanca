@extends('adminlte::page')

@section('title', 'Metodo Pago')


@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success float-right" id="btnNuevo"><i class="fas fa-plus-circle"></i> Registrar</button>
            <h4>Listado de métodos de pago</h4>
        </div>
        <div class="card-body">
            <table class="table" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>DESCRIPCIÓN</th>
                        <th width=20></th>
                        <th width=20></th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($paymentmethods as $paymeth)
                        <tr>
                            <td>{{ $paymeth->id }}</td>
                            <td>{{ $paymeth->name }}</td>
                            <td>{{ $paymeth->description }}</td>
                            <td>
                                <button class="btnEditar btn btn-primary" id="{{ $paymeth->id }}"><i
                                        class="fa fa-edit"></i></button>
                            </td>
                            <td>
                                <form action="{{ route('admin.paymentmethods.destroy', $paymeth->id) }}" method="POST"
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
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Metodo de pago</h5>
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
                url: "{{ route('admin.paymentmethods.create') }}",
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
                url: "{{ route('admin.paymentmethods.edit', '_id') }}".replace('_id', id),
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
                        /*Swal.fire({
                        title: "Marca eliminada!",
                        text: "Porceso exitoso.",
                        icon: "Satisfactorio"
                        });*/
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
                title: "Error de proceso",
                text: "{{ session('error') }}",
                icon: "error"
            });
        </script>
    @endif

@stop
