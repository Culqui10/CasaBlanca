
<div class="form-group row">
    <!-- Campo de búsqueda -->
    <div class="col-md-6">
        {!! Form::label('search_pensionista', 'Buscar pensionista') !!}
        <div class="input-group">
            {!! Form::text('search_pensionista', null, [
                'class' => 'form-control', 
                'placeholder' => 'Buscar pensionista',
                'id' => 'search_pensionista', // ID para identificar el campo
                'required'
            ]) !!}
            <div class="input-group-append">
                <button class="btn btn-primary" id="btn-search" type="button">Buscar</button>
            </div>
        </div>
    </div>

    <!-- Nombre del pensionista -->
    <div class="col-md-6">
        {!! Form::label('pensionista_name', 'Pensionista') !!}
        {!! Form::text('pensioner_id', null, [
            'class' => 'form-control', 
            'id' => 'pensionista_name', // ID para actualizar el nombre dinámicamente
            'readonly'
        ]) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('paymentmethod', 'Método de pago') !!}
        {!! Form::select('paymentmethod_id', $methodpayment, null, 
        ['class' => 'form-control', 
        'required'
        ]) !!}
    </div>

    <div class="col.md-6">
        {!! Form::label('price', 'Saldo') !!}
        {!! Form::number('price', null, [
            'class' => 'form-control', 
            'placeholder' => 'Ingrese el precio',
            'required',
            'step' => '0.01', 
            'min' => '0',     
        ]) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('date', 'Fecha') !!}
        {!! Form::date('date', isset($payment) ? $payment->date->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d'), [
            'class' => 'form-control', 
            'placeholder' => 'Ingrese fecha',
            'required',
        ]) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, 
    ['class'=>'form-control', 
    'style'=>'height:60px;', 
    'placeholder'=>'Ingrese la descripción',
    ]) !!}
</div>

<script>
    document.getElementById('btn-search').addEventListener('click', function() {
    const query = document.getElementById('search_pensionista').value;

    if (!query) {
        alert('Por favor, ingrese un nombre o apellido para buscar.');
        return;
    }

    fetch(`/admin/pensioners/search?query=${query}`)

    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('pensionista_name').value = data.data.name;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Error al buscar el pensionista. Revisa la consola para más detalles.');
    });
});

</script>