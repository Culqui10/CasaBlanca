{!! Form::open(['route'=>'admin.categorias.store','files'=>true]) !!}
@include('admin.categorias.partials.form')            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-danger">
                <i class="fas fa-window-close"></i> Cancelar
            </a>
{!! Form::close() !!}
