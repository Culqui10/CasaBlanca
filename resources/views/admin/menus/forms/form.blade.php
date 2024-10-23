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
    {!! Form::text('price', null, 
    ['class'=>'form-control', 
    'placeholder'=>'Ingrese el precio',
    'required',
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