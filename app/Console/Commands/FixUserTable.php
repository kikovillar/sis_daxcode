<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixUserTable extends Command
{
    protected $signature = 'fix:user-table';
    protected $description = 'Fix user table by adding missing columns';

    public function handle()
    {
        $this->info('Verificando e corrigindo tabela users...');

        // Lista de colunas que devem existir
        $requiredColumns = [
            'tamanho_camisa' => "ENUM('PP', 'P', 'M', 'G', 'GG', 'XG', 'XXG') NULL",
            'documento_rg' => 'VARCHAR(255) NULL',
            'documento_cnh' => 'VARCHAR(255) NULL',
            'documento_cpf' => 'VARCHAR(255) NULL',
            'documento_foto_3x4' => 'VARCHAR(255) NULL',
            'data_nascimento' => 'DATE NULL',
            'idade' => 'INT NULL',
            'rg' => 'VARCHAR(20) NULL',
            'cpf' => 'VARCHAR(14) NULL',
            'sexo' => "ENUM('masculino', 'feminino', 'outro') NULL",
            'telefone' => 'VARCHAR(20) NULL',
            'bf' => 'VARCHAR(255) NULL',
            'estado_civil' => "ENUM('solteiro', 'casado', 'divorciado', 'viuvo', 'uniao_estavel') NULL",
            'tem_filhos' => 'BOOLEAN DEFAULT FALSE',
            'quantidade_filhos' => 'INT NULL',
            'endereco' => 'VARCHAR(255) NULL',
            'bairro' => 'VARCHAR(100) NULL',
            'cidade' => 'VARCHAR(100) NULL',
            'estado' => 'VARCHAR(2) NULL',
            'cep' => 'VARCHAR(10) NULL',
            'urgencia_nome_contato' => 'VARCHAR(255) NULL',
            'urgencia_telefone_contato' => 'VARCHAR(20) NULL',
            'tem_deficiencia' => 'BOOLEAN DEFAULT FALSE',
            'descricao_deficiencia' => 'TEXT NULL',
            'tem_condicao_saude' => 'BOOLEAN DEFAULT FALSE',
            'descricao_saude' => 'TEXT NULL',
            'tem_alergia' => 'BOOLEAN DEFAULT FALSE',
            'descricao_alergia' => 'TEXT NULL',
            'usa_medicamento' => 'BOOLEAN DEFAULT FALSE',
            'qual_medicamento' => 'TEXT NULL',
            'ensino_pre_escolar' => 'BOOLEAN DEFAULT FALSE',
            'ensino_fundamental_concluido' => 'BOOLEAN DEFAULT FALSE',
            'ensino_fundamental_cursando' => 'BOOLEAN DEFAULT FALSE',
            'ensino_fundamental_instituicao' => 'VARCHAR(255) NULL',
            'ensino_medio_concluido' => 'BOOLEAN DEFAULT FALSE',
            'ensino_medio_cursando' => 'BOOLEAN DEFAULT FALSE',
            'ensino_medio_instituicao' => 'VARCHAR(255) NULL',
            'ensino_tecnico_concluido' => 'BOOLEAN DEFAULT FALSE',
            'ensino_tecnico_cursando' => 'BOOLEAN DEFAULT FALSE',
            'ensino_tecnico_instituicao' => 'VARCHAR(255) NULL',
            'ensino_superior_concluido' => 'BOOLEAN DEFAULT FALSE',
            'superior_cursando' => 'BOOLEAN DEFAULT FALSE',
            'superior_trancado' => 'BOOLEAN DEFAULT FALSE',
            'superior_instituicao' => 'VARCHAR(255) NULL',
            'pos_graduacao_concluido' => 'BOOLEAN DEFAULT FALSE',
            'pos_graduacao_cursando' => 'BOOLEAN DEFAULT FALSE',
            'pos_graduacao_instituicao' => 'VARCHAR(255) NULL',
            'curso_1' => 'VARCHAR(255) NULL',
            'curso_1_instituicao' => 'VARCHAR(255) NULL',
            'curso_2' => 'VARCHAR(255) NULL',
            'curso_2_instituicao' => 'VARCHAR(255) NULL',
            'curso_3' => 'VARCHAR(255) NULL',
            'curso_3_instituicao' => 'VARCHAR(255) NULL',
            'situacao_profissional' => "ENUM('empregado', 'desempregado', 'autonomo', 'estudante', 'aposentado') NULL",
            'experiencia_1_instituicao' => 'VARCHAR(255) NULL',
            'experiencia_1_ano' => 'VARCHAR(10) NULL',
            'experiencia_1_funcao' => 'VARCHAR(255) NULL',
            'experiencia_1_atividades' => 'TEXT NULL',
            'experiencia_2_instituicao' => 'VARCHAR(255) NULL',
            'experiencia_2_ano' => 'VARCHAR(10) NULL',
            'experiencia_2_funcao' => 'VARCHAR(255) NULL',
            'experiencia_2_atividades' => 'TEXT NULL',
            'nivel_ingles' => "ENUM('basico', 'avancado', 'fluente') NULL",
            'desenvolveu_sistemas' => 'BOOLEAN DEFAULT FALSE',
            'ja_empreendeu' => 'BOOLEAN DEFAULT FALSE',
            'disponibilidade_dias' => 'JSON NULL',
            'disponibilidade_horario' => 'VARCHAR(255) NULL',
        ];

        $addedColumns = 0;
        
        foreach ($requiredColumns as $column => $definition) {
            if (!Schema::hasColumn('users', $column)) {
                try {
                    DB::statement("ALTER TABLE users ADD COLUMN `{$column}` {$definition}");
                    $this->info("✅ Adicionada coluna: {$column}");
                    $addedColumns++;
                } catch (\Exception $e) {
                    $this->error("❌ Erro ao adicionar coluna {$column}: " . $e->getMessage());
                }
            } else {
                $this->line("⚪ Coluna já existe: {$column}");
            }
        }

        if ($addedColumns > 0) {
            $this->info("🎉 {$addedColumns} colunas foram adicionadas com sucesso!");
        } else {
            $this->info("✅ Todas as colunas já existem na tabela users.");
        }

        return 0;
    }
}