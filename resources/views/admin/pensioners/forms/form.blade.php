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
        {!! Form::label('lastname', 'Apellidos') !!}
        {!! Form::text('lastname', null, 
        ['class'=>'form-control', 
        'placeholder'=>'Ingrese apellidos',
        'required',
        ]) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('phone', 'Teléfono') !!}
        {!! Form::tel('phone', null, [
            'class' => 'form-control', 
            'placeholder' => 'Ingrese teléfono',
            'required',
            'pattern' => '[0-9]{9,15}', // números con longitud mínima y máxima
            'title' => 'Ingrese solo números (mínimo 9 y máximo 15 dígitos)',
        ]) !!}
    </div>
    
    <div class="col-md-6">
        {!! Form::label('location', 'Lugar de procedencia') !!}
        {!! Form::text('location', null, 
        ['class'=>'form-control', 
        'placeholder'=>'Ingrese procedencia',
        'required',
        ]) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('name_representative', 'Representante') !!}
        {!! Form::text('name_representative', null, 
        ['class'=>'form-control', 
        'placeholder'=>'Ingrese nombre',
        'required',
        ]) !!}
    </div>

    <div class="col-md-6">
        {!! Form::label('phone_representative', 'Teléfono') !!}
        {!! Form::tel('phone_representative', null, [
            'class' => 'form-control', 
            'placeholder' => 'Ingrese teléfono',
            'required',
            'pattern' => '[0-9]{9,15}', // números con longitud mínima y máxima
            'title' => 'Ingrese solo números (mínimo 9 y máximo 15 dígitos)',
        ]) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('date', 'Fecha') !!}
        {!! Form::date('date', isset($pensioner) ? $pensioner->date->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d'), [
            'class' => 'form-control', 
            'placeholder' => 'Ingrese fecha',
            'required',
        ]) !!}
    </div>
</div>



