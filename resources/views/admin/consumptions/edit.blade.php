{!! Form::model($consumption, ['route' => ['admin.consumptions.update', $consumption->id], 'method' => 'put']) !!}
<div class="form-group row">
    {!! Form::hidden('pensioner_id', $consumption->pensioner_id ?? null, ['id' => 'pensioner_id']) !!}

    <div class="col-md-12">
        {!! Form::label('pensionista_name', 'Pensionista') !!}
        {!! Form::text('pensionista_name', $consumption->pensioner->name . ' ' . $consumption->pensioner->lastname, [
            'class' => 'form-control',
            'id' => 'pensionista_name',
            'readonly',
        ]) !!}
    </div>
</div>

@foreach ($details as $type => $data)
    <h5 class="mt-3 text-primary">{{ ucfirst($type) }}</h5>
    <div class="form-group row">
        <div class="col-md-6">
            {!! Form::label("details[{$type}][menu_id]", 'Menú') !!}
            {!! Form::select(
                "details[{$type}][menu_id]",
                $menus[$type]->pluck('name', 'id')->toArray() ?? [],
                $data['menu_id'] ?? null,
                [
                    'class' => 'form-control menu-select',
                    'data-type' => $type,
                    'placeholder' => 'Seleccione un menú',
                ],
            ) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label("details[{$type}][price]", 'Precio S/.') !!}
            {!! Form::number("details[{$type}][price]", $data['price'] ?? null, [
                'class' => 'form-control price-input',
                'data-type' => $type,
                'readonly',
            ]) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            {!! Form::label("details[{$type}][aditional]", 'Adicional') !!}
            {!! Form::textarea("details[{$type}][aditional]", $data['aditional'] ?? null, [
                'class' => 'form-control aditional-input',
                'data-type' => $type,
                'rows' => 2,
                'placeholder' => 'Ingrese el adicional',
            ]) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label("details[{$type}][aditional_cost]", 'Costo adicional S/.') !!}
            {!! Form::number("details[{$type}][aditional_cost]", $data['aditional_cost'] ?? 0, [
                'class' => 'form-control aditional-cost-input',
                'data-type' => $type,
                'step' => '0.01',
                'min' => '0',
            ]) !!}
        </div>
    </div>
@endforeach

<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('date', 'Fecha') !!}
        {!! Form::date(
            'date',
            isset($consumption) ? $consumption->date->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d'),
            [
                'class' => 'form-control',
                'placeholder' => 'Ingrese fecha',
                'required',
            ],
        ) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('total', 'Total S/.') !!}
        {!! Form::number('total', $consumption->total, [
            'class' => 'form-control',
            'id' => 'total',
            'readonly',
        ]) !!}
    </div>
</div>

<button type="submit" class="btn btn-success"><i class="fas fa-save"> Actualizar</i></button>
<a type="button" href="{{ route('admin.consumptions.index') }}" class="btn btn-danger"><i class="fas fa-window-close">
        Cancelar</i></a>
{!! Form::close() !!}

<script>
   document.addEventListener('DOMContentLoaded', function () {
    function recalculateTotal() {
        let total = 0;

        // Recorremos todos los selects de menú para calcular el total
        document.querySelectorAll('.menu-select').forEach((menuSelect) => {
            const type = menuSelect.getAttribute('data-type');
            const priceInput = document.querySelector(`.price-input[data-type="${type}"]`);
            const aditionalCostInput = document.querySelector(`.aditional-cost-input[data-type="${type}"]`);

            const price = parseFloat(priceInput.value) || 0; // Precio del menú seleccionado
            const aditionalCost = parseFloat(aditionalCostInput.value) || 0; // Costo adicional

            total += price + aditionalCost; // Sumar al total
        });

        // Actualizamos el total en el campo correspondiente
        const totalField = document.getElementById('total');
        if (totalField) {
            totalField.value = total.toFixed(2);
        }
    }

    // Evento para actualizar el precio al seleccionar un menú
    document.querySelectorAll('.menu-select').forEach((menuSelect) => {
        menuSelect.addEventListener('change', function () {
            const type = this.getAttribute('data-type');
            const priceInput = document.querySelector(`.price-input[data-type="${type}"]`);
            const menuId = this.value;

            if (menuId) {
                // Llamamos al backend para obtener el precio del menú seleccionado
                fetch(`/admin/menus/get-price/${menuId}`)
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            priceInput.value = data.price; // Actualizamos el precio en el input

                            // Recalculamos el total después de cambiar el precio
                            recalculateTotal();
                        }
                    })
                    .catch((error) => {
                        console.error('Error al obtener el precio:', error);
                        priceInput.value = ''; // Limpiar el precio si hay un error
                        recalculateTotal();
                    });
            } else {
                priceInput.value = '';
                recalculateTotal();
            }
        });
    });

    // Evento para actualizar el total al cambiar el costo adicional
    document.querySelectorAll('.aditional-cost-input').forEach((input) => {
        input.addEventListener('input', recalculateTotal);
    });

    // Calcular el total inicial al cargar la página
    recalculateTotal();
});

</script>