{!! Form::open(['route' => 'admin.payments.store']) !!}
@include('admin.payments.forms.form')
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
<a href="{{ route('admin.payments.index') }}" class="btn btn-danger">
    <i class="fas fa-window-close"></i> Cancelar
</a>
{!! Form::close() !!}
