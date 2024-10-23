<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentmethod extends Model
{
    use HasFactory;
    // Especificar el nombre de la tabla -> Evitar que Laravel no use las convenciones predeterminadas
    protected $table = 'paymentmethods';

    protected $guarded=[];
}
