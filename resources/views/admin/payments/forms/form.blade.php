<div class="form-group row">
    {!! Form::hidden('pensioner_id', $payment->pensioner_id ?? null, ['id' => 'pensioner_id']) !!}
    <div class="col-md-6">
        {!! Form::label('search_pensionista', 'Buscar pensionista') !!}
        <div class="input-group">
            {!! Form::text('search_pensionista', $payment->pensioner->name ?? '', [
                'class' => 'form-control',
                'placeholder' => 'Buscar pensionista',
                'id' => 'search_pensionista',
            ]) !!}
            <div class="input-group-append">
                <button class="btn btn-primary" id="btn-search" type="button">Buscar</button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        {!! Form::label('pensionista_name', 'Pensionista') !!}
        {!! Form::text('pensionista_name', $payment->pensioner->name ?? '', [
            'class' => 'form-control',
            'id' => 'pensionista_name',
            'readonly',
        ]) !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('paymentmethod_id', 'Método de pago') !!}
        {!! Form::select('paymentmethod_id', $methodpayment, $payment->paymentmethod_id ?? null, [
            'class' => 'form-control',
        ]) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('price', 'Monto de pago S/.') !!}
        {!! Form::number('price', $payment->total ?? null, [
            'class' => 'form-control',
            'placeholder' => 'Ingrese el precio',
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
    {!! Form::textarea('description', $payment->description ?? null, [
        'class' => 'form-control',
        'rows' => 2,
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
                    document.getElementById('pensioner_id').value = data.data.id;
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
