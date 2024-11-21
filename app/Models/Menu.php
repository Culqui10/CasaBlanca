<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    // Especificar el nombre de la tabla -> Evitar que Laravel no use las convenciones predeterminadas
    protected $table = 'menus';

    protected $guarded=[];
    
    public function typefood()
{
    return $this->belongsTo(Typefood::class, 'typefood_id');
}

}
