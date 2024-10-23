{!! Form::open(['route' => 'admin.paymentmethods.store', 'files' => true]) !!}
    @include('admin.paymentmethods.forms.form')

    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-window-close"></i> Cancelar</button>
{!! Form::close() !!}
