<div class="form-group row">
    {!! Form::hidden('pensioner_id', $consumption->pensioner_id ?? null, ['id' => 'pensioner_id']) !!}
    <div class="col-md-6">
        {!! Form::label('search_pensionista', 'Buscar pensionista') !!}
        <div class="input-group">
            {!! Form::text('search_pensionista', $consumption->pensioner->name ?? '', [
                'class' => 'form-control',
                'placeholder' => 'Buscar pensionista',
                'id' => 'search_pensionista',
                'required',
            ]) !!}
            <div class="input-group-append">
                <button class="btn btn-primary" id="btn-search" type="button">Buscar</button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        {!! Form::label('pensionista_name', 'Pensionista') !!}
        {!! Form::text('pensionista_name', $consumption->pensioner->name ?? '', [
            'class' => 'form-control',
            'id' => 'pensionista_name',
            'readonly',
        ]) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12">
        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
            <label class="btn btn-outline-secondary typefood w-33 active" data-typefood="desayuno">
                <input type="radio" name="typefood" value="desayuno" autocomplete="off" checked>
                Desayuno
            </label>
            <label class="btn btn-outline-secondary typefood w-33" data-typefood="almuerzo">
                <input type="radio" name="typefood" value="almuerzo" autocomplete="off">
                Almuerzo
            </label>
            <label class="btn btn-outline-secondary typefood w-33" data-typefood="cena">
                <input type="radio" name="typefood" value="cena" autocomplete="off">
                Cena
            </label>
        </div>
    </div>
</div>
@foreach (['desayuno', 'almuerzo', 'cena'] as $type)
    <h5 class="mt-3 text-primary">{{ ucfirst($type) }}</h5>
    <div class="form-group row">
        <div class="col-md-6">
            {!! Form::label("details[{$type}][menu_id]", 'Menú') !!}
            {!! Form::select(
                "details[{$type}][menu_id]",
                $menus[$type]->pluck('name', 'id')->toArray() ?? [],
                $details[$type]['menu_id'] ?? null,
                [
                    'class' => 'form-control menu-select',
                    'data-type' => $type,
                    'placeholder' => 'Seleccione un menú',
                ]
            ) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label("details[{$type}][price]", 'Precio S/.') !!}
            {!! Form::number("details[{$type}][price]", $details[$type]['price'] ?? null, [
                'class' => 'form-control price-input',
                'data-type' => $type,
                'readonly',
            ]) !!}
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            {!! Form::label("details[{$type}][aditional]", 'Adicional') !!}
            {!! Form::textarea("details[{$type}][aditional]", $details[$type]['aditional'] ?? null, [
                'class' => 'form-control aditional-input',
                'data-type' => $type,
                'rows' => 2,
                'placeholder' => 'Ingrese el adicional',
            ]) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label("details[{$type}][aditional_cost]", 'Costo adicional S/.') !!}
            {!! Form::number("details[{$type}][aditional_cost]", $details[$type]['aditional_cost'] ?? 0, [
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
        {!! Form::number('total', null, [
            'class' => 'form-control',
            'id' => 'total',
            'readonly',
        ]) !!}
    </div>
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


    document.querySelectorAll('.typefood').forEach(button => {
        button.addEventListener('click', function() {
            // Eliminar la clase activa de todos los botones
            document.querySelectorAll('.typefood').forEach(btn => btn.classList.remove('active'));
            // Agregar la clase activa al botón seleccionado
            this.classList.add('active');

            const typefood = this.getAttribute('data-typefood');

            // Realizar la solicitud AJAX para obtener los menús según el tipo de comida
            fetch(`/admin/menus/filter?typefood=${typefood}`)

                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Limpiar el campo de menú
                        const menuSelect = document.getElementById('menu_id');
                        menuSelect.innerHTML = '<option value="">Seleccione un menú</option>';

                        // Llenar los menús en el combo box
                        data.menus.forEach(menu => {
                            const option = document.createElement('option');
                            option.value = menu.id;
                            option.textContent = menu.name;
                            menuSelect.appendChild(option);
                        });

                        // Limpiar el precio al cambiar de tipo de comida
                        document.getElementById('price').value = '';
                    } else {
                        alert('No hay menús disponibles para este tipo de comida.');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Error al cargar los menús. Revisa la consola para más detalles.');
                });
        });
    });

    // Mostrar el precio del menú seleccionado
    document.getElementById('menu_id').addEventListener('change', function() {
        const menuId = this.value;

        if (!menuId) {
            document.getElementById('price').value = '';
            document.getElementById('total').value = '';
            return;
        }

        // Solicitar el precio del menú seleccionado
        fetch(`/admin/menus/get-price/${menuId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mostrar el precio en el campo de precio
                    document.getElementById('price').value = data.price;

                    // Calcular el total (precio + costo adicional)
                    const aditionalCost = parseFloat(document.getElementById('aditional_cost').value) || 0;
                    const total = parseFloat(data.price) + aditionalCost;
                    document.getElementById('total').value = total.toFixed(2);
                } else {
                    alert('No se pudo obtener el precio del menú.');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error al obtener el precio del menú. Revisa la consola para más detalles.');
            });
    });

    // Recalcular el total cuando se cambie el costo adicional
    document.getElementById('aditional_cost').addEventListener('input', function() {
        const menuPrice = parseFloat(document.getElementById('price').value) || 0;
        const aditionalCost = parseFloat(this.value) || 0;
        const total = menuPrice + aditionalCost;
        document.getElementById('total').value = total.toFixed(2);
    });
</script>

    
    <button type="submit" class="btn btn-success"><i class="fas fa-save"> Actualizar</i></button>
    <a type="button" href="{{ route('admin.consumptions.index') }}" class="btn btn-danger"><i class="fas fa-window-close"> Cancelar</i></a>

