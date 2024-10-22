{!! Form::open(['route'=>'admin.productos.store', 'files'=>true]) !!}
            @include('admin.productos.partials.form')
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar</button>
            <a href="{{ route('admin.productos.index') }}" class="btn btn-danger">
                <i class="fas fa-window-close"></i> Cancelar
            </a>
{!! Form::close() !!}


