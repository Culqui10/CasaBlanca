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
        $p1->save();

        $p2 = new Paymentmethod();
        $p2->name='BN';
        $p2->description='Banco de la nación';
        $p2->save();

        $p3 = new Paymentmethod();
        $p3->name='BCP';
        $p3->description='Banco de crédito del Perú';
        $p3->save();

        $p4 = new Paymentmethod();
        $p4->name='Efectivo';
        $p4->save();

    }
}
