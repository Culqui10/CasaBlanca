<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, 
    ['class'=>'form-control', 
    'placeholder'=>'Ingrese nombre de la categoria',
    'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('name', 'Familia') !!}
    {!! Form::select('familia_id',$familias, null,
    ['class'=>'form-control', 
    'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, 
    ['class'=>'form-control', 
    'style'=>'height:100px;', 
    'placeholder'=>'Ingrese la descripción de la categoria',
    ]) !!}
</div>