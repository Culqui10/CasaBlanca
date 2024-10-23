<?php

namespace Database\Seeders;

use App\Models\Typefood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypefoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $t1 = new Typefood();
        $t1->name = 'Desayuno';
        $t1->description = 'Desde 07:00 AM - 11:00 AM';
        $t1->save();

        $t2 = new Typefood();
        $t2->name = 'Almuerzo';
        $t2->description = 'Desde 11:00 AM - 04:00 PM';
        $t2->save();

        $t3 = new Typefood();
        $t3->name = 'Cena';
        $t3->description = 'Desde 06:30 PM - 10:00 PM';
        $t3->save();
    }
}
