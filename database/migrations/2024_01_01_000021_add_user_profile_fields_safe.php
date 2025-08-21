<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verificar se as colunas já existem antes de adicionar
            if (!Schema::hasColumn('users', 'documento_rg')) {
                // Documentos
                $table->string('documento_rg')->nullable();
                $table->string('documento_cnh')->nullable();
                $table->string('documento_cpf')->nullable();
                $table->string('documento_foto_3x4')->nullable();
                
                // Dados Pessoais
                $table->date('data_nascimento')->nullable();
                $table->integer('idade')->nullable();
                $table->string('rg')->nullable();
                $table->string('cpf')->nullable();
                $table->enum('sexo', ['masculino', 'feminino', 'outro'])->nullable();
                $table->string('telefone')->nullable();
                $table->string('bf')->nullable(); // Benefício
                $table->enum('estado_civil', ['solteiro', 'casado', 'divorciado', 'viuvo', 'uniao_estavel'])->nullable();
                $table->boolean('tem_filhos')->default(false);
                $table->integer('quantidade_filhos')->nullable();
                
                // Endereço
                $table->string('endereco')->nullable();
                $table->string('bairro')->nullable();
                $table->string('cidade')->nullable();
                $table->string('estado')->nullable();
                $table->string('cep')->nullable();
                
                // Contato de Urgência
                $table->string('urgencia_nome_contato')->nullable();
                $table->string('urgencia_telefone_contato')->nullable();
                
                // Informações Físicas
                $table->enum('tamanho_camisa', ['PP', 'P', 'M', 'G', 'GG', 'XG', 'XXG'])->nullable();
                
                // Saúde
                $table->boolean('tem_deficiencia')->default(false);
                $table->text('descricao_deficiencia')->nullable();
                $table->boolean('tem_condicao_saude')->default(false);
                $table->text('descricao_saude')->nullable();
                $table->boolean('tem_alergia')->default(false);
                $table->text('descricao_alergia')->nullable();
                $table->boolean('usa_medicamento')->default(false);
                $table->text('qual_medicamento')->nullable();
                
                // Educação
                $table->boolean('ensino_pre_escolar')->default(false);
                $table->boolean('ensino_fundamental_concluido')->default(false);
                $table->boolean('ensino_fundamental_cursando')->default(false);
                $table->string('ensino_fundamental_instituicao')->nullable();
                $table->boolean('ensino_medio_concluido')->default(false);
                $table->boolean('ensino_medio_cursando')->default(false);
                $table->string('ensino_medio_instituicao')->nullable();
                $table->boolean('ensino_tecnico_concluido')->default(false);
                $table->boolean('ensino_tecnico_cursando')->default(false);
                $table->string('ensino_tecnico_instituicao')->nullable();
                $table->boolean('ensino_superior_concluido')->default(false);
                $table->boolean('superior_cursando')->default(false);
                $table->boolean('superior_trancado')->default(false);
                $table->string('superior_instituicao')->nullable();
                $table->boolean('pos_graduacao_concluido')->default(false);
                $table->boolean('pos_graduacao_cursando')->default(false);
                $table->string('pos_graduacao_instituicao')->nullable();
                
                // Cursos Extras
                $table->string('curso_1')->nullable();
                $table->string('curso_1_instituicao')->nullable();
                $table->string('curso_2')->nullable();
                $table->string('curso_2_instituicao')->nullable();
                $table->string('curso_3')->nullable();
                $table->string('curso_3_instituicao')->nullable();
                
                // Profissional
                $table->enum('situacao_profissional', ['empregado', 'desempregado', 'autonomo', 'estudante', 'aposentado'])->nullable();
                
                // Experiência Profissional 1
                $table->string('experiencia_1_instituicao')->nullable();
                $table->string('experiencia_1_ano')->nullable();
                $table->string('experiencia_1_funcao')->nullable();
                $table->text('experiencia_1_atividades')->nullable();
                
                // Experiência Profissional 2
                $table->string('experiencia_2_instituicao')->nullable();
                $table->string('experiencia_2_ano')->nullable();
                $table->string('experiencia_2_funcao')->nullable();
                $table->text('experiencia_2_atividades')->nullable();
                
                // Habilidades
                $table->enum('nivel_ingles', ['basico', 'avancado', 'fluente'])->nullable();
                $table->boolean('desenvolveu_sistemas')->default(false);
                $table->boolean('ja_empreendeu')->default(false);
                
                // Disponibilidade
                $table->json('disponibilidade_dias')->nullable(); // Array de dias da semana
                $table->string('disponibilidade_horario')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'documento_rg', 'documento_cnh', 'documento_cpf', 'documento_foto_3x4',
                'data_nascimento', 'idade', 'rg', 'cpf', 'sexo', 'telefone', 'bf',
                'estado_civil', 'tem_filhos', 'quantidade_filhos',
                'endereco', 'bairro', 'cidade', 'estado', 'cep',
                'urgencia_nome_contato', 'urgencia_telefone_contato',
                'tamanho_camisa',
                'tem_deficiencia', 'descricao_deficiencia', 'tem_condicao_saude', 'descricao_saude',
                'tem_alergia', 'descricao_alergia', 'usa_medicamento', 'qual_medicamento',
                'ensino_pre_escolar', 'ensino_fundamental_concluido', 'ensino_fundamental_cursando', 'ensino_fundamental_instituicao',
                'ensino_medio_concluido', 'ensino_medio_cursando', 'ensino_medio_instituicao',
                'ensino_tecnico_concluido', 'ensino_tecnico_cursando', 'ensino_tecnico_instituicao',
                'ensino_superior_concluido', 'superior_cursando', 'superior_trancado', 'superior_instituicao',
                'pos_graduacao_concluido', 'pos_graduacao_cursando', 'pos_graduacao_instituicao',
                'curso_1', 'curso_1_instituicao', 'curso_2', 'curso_2_instituicao', 'curso_3', 'curso_3_instituicao',
                'situacao_profissional',
                'experiencia_1_instituicao', 'experiencia_1_ano', 'experiencia_1_funcao', 'experiencia_1_atividades',
                'experiencia_2_instituicao', 'experiencia_2_ano', 'experiencia_2_funcao', 'experiencia_2_atividades',
                'nivel_ingles', 'desenvolveu_sistemas', 'ja_empreendeu',
                'disponibilidade_dias', 'disponibilidade_horario'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};