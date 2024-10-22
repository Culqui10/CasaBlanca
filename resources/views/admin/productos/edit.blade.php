
{!! Form::model($productos, ['route'=>['admin.productos.update', $productos],'method' => 'put','files'=>true]) !!}
@include('admin.productos.partials.form')
<button type="submit" class="btn btn-success"><i class="fas fa-save"> Actualizar</i></button>
<a type="button" href="{{route('admin.productos.index')}}" class="btn btn-danger"><i class="fas fa-window-close"> Cancelar</i></a>
{!! Form::close() !!}