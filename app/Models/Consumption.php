<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    use HasFactory;
    // Especificar el nombre de la tabla -> Evitar que Laravel no use las convenciones predeterminadas
    protected $table = 'consumptions';
    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
    protected $guarded = [];


    public function details()
    {
        return $this->hasMany(ConsumptionDetail::class, 'consumption_id');
    }

    public function pensioner()
    {
        return $this->belongsTo(Pensioner::class, 'pensioner_id');
    }
}
