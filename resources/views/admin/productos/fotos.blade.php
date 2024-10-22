<div class="row">
    <?php foreach ($fotos as $f) : ?>
        <?php if ($f['url_foto'] != '-') { ?>
            <div class="col-lg-4 col-md-4 col-xs-4 thumb text-center">

                <a class="thumbnail" href="#">
                    <img class="img-responsive" src="{{ asset($f['url_foto']) }}" alt="" height="50" width="50">

                </a>
                <div class="row">
                    <?php $id = $f->product_id . '-' . $f->id; ?>
                    <form action="{{route('admin.foto.update', $id)}}" method="post" class="frmPerfilFoto">
                        @method('put')
                        @csrf
                        <button type="submit" class="btn btn-primary">Perfil</button>
                    </form>
                    
                    <form action="{{route('admin.foto.destroy', $id)}}" method="post" class="frmEliminarFoto">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>

            </div>
        <?php } ?>
    <?php endforeach; ?>

</div>