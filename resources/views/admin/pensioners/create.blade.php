{!! Form::open(['route' => 'admin.pensioners.store']) !!}
@include('admin.pensioners.forms.form')
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
<a href="{{ route('admin.pensioners.index') }}" class="btn btn-danger">
    <i class="fas fa-window-close"></i> Cancelar
</a>
{!! Form::close() !!}
