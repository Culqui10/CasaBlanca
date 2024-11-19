<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accountstatus extends Model
{
    use HasFactory;

    protected $table = 'accountstatus';

    protected $guarded = [];

    public function pensioner()
    {
        return $this->belongsTo(Pensioner::class);
    }

    public function updateStatus()
    {
        if ($this->current_balance < 0) {
            $this->status = 'pendiente';
        } elseif ($this->current_balance <= 20) {
            $this->status = 'agotándose';
        } else {
            $this->status = 'todos';
        }

        $this->save();
    }
    
}
