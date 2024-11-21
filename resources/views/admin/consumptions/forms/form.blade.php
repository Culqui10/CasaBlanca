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

<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('menu_id', 'Menú') !!}
        {!! Form::select('menu_id', [], null, [
            'class' => 'form-control',
            'id' => 'menu_id',
            'required'
        ]) !!}
    </div>

    <div class="col-md-6">
        {!! Form::label('price', 'Precio S/.') !!}
        {!! Form::number('price', null, [
            'class' => 'form-control',
            'id' => 'price',
            'readonly',
        ]) !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-md-6">
        {!! Form::label('aditional', 'Adicional') !!}
        {!! Form::textarea('aditional', null, [
            'class' => 'form-control',
            'rows' => 2,
            'placeholder' => 'Ingrese el adicional',
        ]) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('aditional_cost', 'Costo adicional S/.') !!}
        {!! Form::number('aditional_cost', null, [
            'class' => 'form-control',
            'id' => 'aditional_cost',
            'placeholder' => 'Ingrese el costo',
            'step' => '0.01',
            'min' => '0',
        ]) !!}
    </div>
</div>

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
