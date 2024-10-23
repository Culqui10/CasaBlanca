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
        $t1->save();

        $t2 = new Typefood();
        $t2->name = 'Almuerzo';
        $t2->save();

        $t3 = new Typefood();
        $t3->name = 'Cena';
        $t3->save();
    }
}
