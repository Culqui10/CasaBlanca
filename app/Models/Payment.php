<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    // Especificar el nombre de la tabla -> Evitar que Laravel no use las convenciones predeterminadas
    protected $table = 'payments';
    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    protected $guarded = [];


    public function pensioner()
    {
        return $this->belongsTo(Pensioner::class);
    }

    public function paymentmethod()
    {
        return $this->belongsTo(Paymentmethod::class);
    }

    public function accountstatus()
    {
        return $this->hasOne(Accountstatus::class);
    }
}
