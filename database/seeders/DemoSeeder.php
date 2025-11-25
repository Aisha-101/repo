<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategorija;
use App\Models\Knyga;
use App\Models\Rekomendacija;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $fantastika = Kategorija::create(['pavadinimas' => 'Fantastika']);
        $romanas = Kategorija::create(['pavadinimas' => 'Romanas']);
        $siaubas = Kategorija::create(['pavadinimas' => 'Siaubas']);

        // Create books for those categories
        $hp = $fantastika->knygos()->create([
            'pavadinimas' => 'Haris Poteris',
            'autorius' => 'J.K. Rowling',
            'aprasymas' => 'Magijos pasaulio nuotykiai.',
            'isbn' => '1234567890'
        ]);

        $mazasis = $romanas->knygos()->create([
            'pavadinimas' => 'Mažasis Princas',
            'autorius' => 'Antoine de Saint-Exupéry',
            'aprasymas' => 'Filosofinė istorija apie draugystę.',
            'isbn' => '9876543210'
        ]);

        // Add recommendations for each book
        $hp->rekomendacijos()->createMany([
            [
                'naudotojas' => 'Jonas',
                'komentaras' => 'Nuostabi knyga!',
                'ivertinimas' => 5,
            ],
            [
                'naudotojas' => 'Aistė',
                'komentaras' => 'Labai įtraukianti istorija.',
                'ivertinimas' => 4,
            ],
        ]);

        $mazasis->rekomendacijos()->create([
            'naudotojas' => 'Mantas',
            'komentaras' => 'Klasika, verta perskaityti kiekvienam.',
            'ivertinimas' => 5,
        ]);
    }
}
