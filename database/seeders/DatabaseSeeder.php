<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Question;
use App\Models\Assessment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Inicializar configurações do sistema
        $this->call([
            SubjectSeeder::class,
            SystemSettingsSeeder::class,
        ]);
        
        // Criar usuários
        $admin = User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $professor1 = User::firstOrCreate(
            ['email' => 'joao@sistema.com'],
            [
                'name' => 'Prof. João Silva',
                'password' => Hash::make('password'),
                'role' => 'professor',
            ]
        );

        $professor2 = User::firstOrCreate(
            ['email' => 'maria@sistema.com'],
            [
                'name' => 'Prof. Maria Santos',
                'password' => Hash::make('password'),
                'role' => 'professor',
            ]
        );

        // Criar alunos
        $alunos = [];
        for ($i = 1; $i <= 20; $i++) {
            $alunos[] = User::firstOrCreate(
                ['email' => "aluno$i@sistema.com"],
                [
                    'name' => "Aluno $i",
                    'password' => Hash::make('password'),
                    'role' => 'aluno',
                ]
            );
        }

        // Criar disciplinas
        $matematica = Subject::firstOrCreate(
            ['name' => 'Matemática'],
            ['description' => 'Disciplina de Matemática Básica']
        );

        $portugues = Subject::firstOrCreate(
            ['name' => 'Português'],
            ['description' => 'Disciplina de Língua Portuguesa']
        );

        $historia = Subject::firstOrCreate(
            ['name' => 'História'],
            ['description' => 'Disciplina de História do Brasil']
        );

        // Criar turmas
        $turma1 = ClassModel::firstOrCreate(
            ['name' => 'Turma A - Matemática'],
            [
                'description' => 'Turma de Matemática do 1º ano',
                'teacher_id' => $professor1->id,
            ]
        );

        $turma2 = ClassModel::firstOrCreate(
            ['name' => 'Turma B - Português'],
            [
                'description' => 'Turma de Português do 1º ano',
                'teacher_id' => $professor2->id,
            ]
        );

        // Matricular alunos nas turmas (apenas se não estiverem já matriculados)
        foreach (array_slice($alunos, 0, 10) as $aluno) {
            if (!$turma1->students()->where('user_id', $aluno->id)->exists()) {
                $turma1->enrollStudent($aluno);
            }
        }

        foreach (array_slice($alunos, 10, 10) as $aluno) {
            if (!$turma2->students()->where('user_id', $aluno->id)->exists()) {
                $turma2->enrollStudent($aluno);
            }
        }

        // Criar questões de matemática
        $questao1 = Question::firstOrCreate(
            [
                'title' => 'Soma Básica',
                'subject_id' => $matematica->id,
            ],
            [
                'content' => 'Quanto é 2 + 2?',
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'points' => 2.0,
            ]
        );

        // Criar opções apenas se a questão foi criada agora
        if ($questao1->wasRecentlyCreated) {
            $questao1->createOptions([
                ['content' => '3', 'is_correct' => false],
                ['content' => '4', 'is_correct' => true],
                ['content' => '5', 'is_correct' => false],
                ['content' => '6', 'is_correct' => false],
            ]);
        }

        $questao2 = Question::firstOrCreate(
            [
                'title' => 'Multiplicação',
                'subject_id' => $matematica->id,
            ],
            [
                'content' => 'Quanto é 5 × 3?',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'points' => 3.0,
            ]
        );

        if ($questao2->wasRecentlyCreated) {
            $questao2->createOptions([
                ['content' => '12', 'is_correct' => false],
                ['content' => '15', 'is_correct' => true],
                ['content' => '18', 'is_correct' => false],
                ['content' => '20', 'is_correct' => false],
            ]);
        }

        $questao3 = Question::firstOrCreate(
            [
                'title' => 'Verdadeiro ou Falso',
                'subject_id' => $matematica->id,
            ],
            [
                'content' => 'O número 10 é par?',
                'type' => 'true_false',
                'difficulty' => 'easy',
                'points' => 1.0,
            ]
        );

        if ($questao3->wasRecentlyCreated) {
            $questao3->createTrueFalseOptions(true);
        }

        $questao4 = Question::firstOrCreate(
            [
                'title' => 'Questão Dissertativa',
                'subject_id' => $matematica->id,
            ],
            [
                'content' => 'Explique o conceito de números primos e dê três exemplos.',
                'type' => 'essay',
                'difficulty' => 'hard',
                'points' => 5.0,
            ]
        );

        // Criar questões de português
        $questao5 = Question::firstOrCreate(
            [
                'title' => 'Gramática',
                'subject_id' => $portugues->id,
            ],
            [
                'content' => 'Qual é o plural de "cidadão"?',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'points' => 2.0,
            ]
        );

        if ($questao5->wasRecentlyCreated) {
            $questao5->createOptions([
                ['content' => 'cidadãos', 'is_correct' => true],
                ['content' => 'cidadões', 'is_correct' => false],
                ['content' => 'cidadans', 'is_correct' => false],
                ['content' => 'cidadãoes', 'is_correct' => false],
            ]);
        }

        // Criar avaliação
        $avaliacao1 = Assessment::firstOrCreate(
            [
                'title' => 'Prova de Matemática - 1º Bimestre',
                'subject_id' => $matematica->id,
                'teacher_id' => $professor1->id,
            ],
            [
                'description' => 'Avaliação sobre operações básicas e conceitos fundamentais',
                'duration_minutes' => 60,
                'max_score' => 11.0,
                'opens_at' => now()->addHours(1),
                'closes_at' => now()->addDays(7),
                'status' => 'published',
            ]
        );

        // Associar questões à avaliação (apenas se não estiverem já associadas)
        if (!$avaliacao1->questions()->exists()) {
            $avaliacao1->questions()->attach([
                $questao1->id => ['order' => 1],
                $questao2->id => ['order' => 2],
                $questao3->id => ['order' => 3],
                $questao4->id => ['order' => 4],
            ]);
        }

        // Associar turma à avaliação (apenas se não estiver já associada)
        if (!$avaliacao1->classes()->where('class_id', $turma1->id)->exists()) {
            $avaliacao1->classes()->attach($turma1->id);
        }

        echo "Dados iniciais criados com sucesso!\n";
        echo "Admin: admin@sistema.com / password\n";
        echo "Professor: joao@sistema.com / password\n";
        echo "Aluno: aluno1@sistema.com / password\n";
    }
}