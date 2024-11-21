<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumptiondetail extends Model
{
    use HasFactory;
    // Especificar el nombre de la tabla -> Evitar que Laravel no use las convenciones predeterminadas
    protected $table = 'consumptiondetails';

    protected $guarded=[];

    protected $fillable = [
        'consumption_id',
        'menu_id',
        'aditional',
        'aditional_cost',
    ];
    
    // Relación con Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Relación con Consumption
    public function consumption()
    {
        return $this->belongsTo(Consumption::class);
    }
}
