
<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el nombre del producto',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('price', 'Precio') !!}
    {!! Form::text('price', null, [
        'class' => 'form-control',
        'placeholder' => 'Ingrese el precio del producto',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('familia_id', 'Familia') !!}
    {!! Form::select('familia_id', $familias, null, [
        'class' => 'form-control', 
        'required'
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('categoria_id', 'Categoría') !!}
    {!! Form::select('categoria_id', $categorias, null, [
        'class' => 'form-control',
        'placeholder' => 'Seleccione una categoría',
        'required',
    ]) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control',
        'style' => 'height:100px;',
        'placeholder' => 'Ingrese la descripción del producto',
    ]) !!}
</div>

<div class="form-group">
    <label for="formFile" class="form-label">Seleccione una imagen</label>
    <input type="file" name="url_foto" class="form-control" accept="img/*">
</div>

<script>
    $('#familia_id').change(function() {
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.categoriabyfamilia', '_id') }}".replace("_id", id),
            type: "GET",
            dataType: "JSON",
            contenttype: "application/json",
            success: function(response) {
                $.each(response, function(key, value) {
                    $('#categoria_id').empty();
                    $('#categoria_id').append(
                        '<option value=' + value.id + '>' + value.name +
                        '</option>');
                });
            }

        });
    });
</script>
