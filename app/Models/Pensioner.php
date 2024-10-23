<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pensioner extends Model
{
    use HasFactory;
    // Especificar el nombre de la tabla -> Evitar que Laravel no use las convenciones predeterminadas
    protected $table = 'pensioners';

    protected $guarded=[];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
    
}
