<?php

namespace Database\Seeders;

use App\Models\Familia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamiliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $b1 = new Familia();
        $b1->name='ENLATADOS';
        $b1->save();

        $b2 = new Familia();
        $b2->name='LACTEOS';
        $b2->save();

        $b3 = new Familia();
        $b3->name='VERDURAS';
        $b3->save();

        $b4 = new Familia();
        $b4->name='FRUTAS';
        $b4->save();
    }
}
