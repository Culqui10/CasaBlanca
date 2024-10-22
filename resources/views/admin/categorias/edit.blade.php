
{!! Form::model($categorias, ['route'=>['admin.categorias.update', $categorias],'method' => 'put']) !!}
@include('admin.categorias.partials.form')
<button type="submit" class="btn btn-success"><i class="fas fa-save"> Actualizar</i></button>
<a type="button" href="{{route('admin.categorias.index')}}" class="btn btn-danger"><i class="fas fa-window-close"> Cancelar</i></a>
{!! Form::close() !!}