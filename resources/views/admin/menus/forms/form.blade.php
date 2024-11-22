

<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('name', 'Nombre') !!}
        {!! Form::text('name', null, 
        ['class'=>'form-control', 
        'placeholder'=>'Ingrese nombre',
        'required',
        ]) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('price', 'Precio S/.') !!}
        {!! Form::number('price', null, [
            'class' => 'form-control', 
            'placeholder' => 'Ingrese el precio',
            'required',
            'step' => '0.01', // Permite valores decimales
            'min' => '0',     // Valor mínimo (opcional, evita precios negativos)
        ]) !!}
    </div>
    
</div>

<div class="form-group row  ">
    <div class="col-md-5">
        {!! Form::label('typefood', 'Tipo de comida') !!}
        {!! Form::select('typefood_id',$typefood, null,
        ['class'=>'form-control', 
        'required',
        ]) !!}
    </div>
    <div class="col-md-7">
        {!! Form::label('description', 'Descripción') !!}
        {!! Form::textarea('description', null, 
        ['class'=>'form-control', 
        'style'=>'height:60px;', 
        'placeholder'=>'Ingrese la descripción',
        ]) !!}
    </div>
    
</div>

