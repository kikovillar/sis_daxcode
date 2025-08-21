<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Matemática',
                'description' => 'Disciplina de Matemática - Números, operações, geometria e álgebra'
            ],
            [
                'name' => 'Português',
                'description' => 'Disciplina de Língua Portuguesa - Gramática, literatura e redação'
            ],
            [
                'name' => 'História',
                'description' => 'Disciplina de História - História do Brasil e mundial'
            ],
            [
                'name' => 'Geografia',
                'description' => 'Disciplina de Geografia - Geografia física e humana'
            ],
            [
                'name' => 'Ciências',
                'description' => 'Disciplina de Ciências - Biologia, física e química básica'
            ],
            [
                'name' => 'Inglês',
                'description' => 'Disciplina de Língua Inglesa - Gramática e conversação'
            ],
            [
                'name' => 'Educação Física',
                'description' => 'Disciplina de Educação Física - Esportes e atividades físicas'
            ],
            [
                'name' => 'Artes',
                'description' => 'Disciplina de Artes - Desenho, pintura e expressão artística'
            ]
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['name' => $subject['name']],
                $subject
            );
        }
    }
}