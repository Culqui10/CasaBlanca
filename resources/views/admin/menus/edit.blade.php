{!! Form::model($menus, ['route' => ['admin.menus.update', $menus], 'method' => 'put']) !!}
@include('admin.menus.forms.form')
<button type="submit" class="btn btn-success"><i class="fas fa-save"> Actualizar</i></button>
<a type="button" href="{{ route('admin.menus.index') }}" class="btn btn-danger"><i class="fas fa-window-close">
        Cancelar</i></a>
{!! Form::close() !!}
