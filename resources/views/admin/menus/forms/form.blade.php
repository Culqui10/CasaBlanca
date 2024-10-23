<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, 
    ['class'=>'form-control', 
    'placeholder'=>'Ingrese nombre',
    'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('price', 'Precio') !!}
    {!! Form::number('price', null, [
        'class' => 'form-control', 
        'placeholder' => 'Ingrese el precio',
        'required',
        'step' => '0.01', // Permite valores decimales
        'min' => '0',     // Valor mínimo (opcional, evita precios negativos)
    ]) !!}
</div>


<div class="form-group">
    {!! Form::label('typefood', 'Tipo de comida') !!}
    {!! Form::select('typefood_id',$typefood, null,
    ['class'=>'form-control', 
    'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, 
    ['class'=>'form-control', 
    'style'=>'height:100px;', 
    'placeholder'=>'Ingrese la descripción',
    ]) !!}
</div>