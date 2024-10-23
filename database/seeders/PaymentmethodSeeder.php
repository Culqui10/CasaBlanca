<?php

namespace Database\Seeders;

use App\Models\Paymentmethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentmethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $p1 = new Paymentmethod();
        $p1->name='Yape';
        $p1->description='Número de yape';
        $p1->save();

        $p2 = new Paymentmethod();
        $p2->name='Banco de la nación';
        $p2->description='04-029029';
        $p2->save();

        $p3 = new Paymentmethod();
        $p3->name='Banco de crédito del Perú';
        $p3->description='040005655123';
        $p3->save();

        $p4 = new Paymentmethod();
        $p4->name='Efectivo';
        $p4->save();

    }
}
