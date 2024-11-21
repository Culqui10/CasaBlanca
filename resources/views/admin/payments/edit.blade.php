{!! Form::model($payment, ['route' => ['admin.payments.update', $payment], 'method' => 'put']) !!}
@include('admin.payments.forms.form')
<button type="submit" class="btn btn-success"><i class="fas fa-save"> Actualizar</i></button>
<a type="button" href="{{ route('admin.payments.index') }}" class="btn btn-danger"><i class="fas fa-window-close"> Cancelar</i></a>
{!! Form::close() !!}
