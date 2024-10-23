{!! Form::model($pensioner , ['route' => ['admin.pensioners.update', $pensioner ], 'method' => 'put']) !!}
@include('admin.pensioners.forms.form')
<button type="submit" class="btn btn-success"><i class="fas fa-save"> Actualizar</i></button>
<a type="button" href="{{ route('admin.pensioners.index') }}" class="btn btn-danger"><i class="fas fa-window-close">
        Cancelar</i></a>
{!! Form::close() !!}
