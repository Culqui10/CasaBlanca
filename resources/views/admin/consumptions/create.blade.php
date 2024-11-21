
{!! Form::open(['route' => 'admin.consumptions.store']) !!}
@csrf
@include('admin.consumptions.forms.form')
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
<a href="{{ route('admin.consumptions.index') }}" class="btn btn-danger">
    <i class="fas fa-window-close"></i> Cancelar
</a>
{!! Form::close() !!}
