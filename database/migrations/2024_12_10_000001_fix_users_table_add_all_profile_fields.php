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
            
            // Documentos
            if (!Schema::hasColumn('users', 'documento_rg')) {
                $table->string('documento_rg')->nullable();
            }
            if (!Schema::hasColumn('users', 'documento_cnh')) {
                $table->string('documento_cnh')->nullable();
            }
            if (!Schema::hasColumn('users', 'documento_cpf')) {
                $table->string('documento_cpf')->nullable();
            }
            if (!Schema::hasColumn('users', 'documento_foto_3x4')) {
                $table->string('documento_foto_3x4')->nullable();
            }
            
            // Dados Pessoais
            if (!Schema::hasColumn('users', 'data_nascimento')) {
                $table->date('data_nascimento')->nullable();
            }
            if (!Schema::hasColumn('users', 'idade')) {
                $table->integer('idade')->nullable();
            }
            if (!Schema::hasColumn('users', 'rg')) {
                $table->string('rg')->nullable();
            }
            if (!Schema::hasColumn('users', 'cpf')) {
                $table->string('cpf')->nullable();
            }
            if (!Schema::hasColumn('users', 'sexo')) {
                $table->enum('sexo', ['masculino', 'feminino', 'outro'])->nullable();
            }
            if (!Schema::hasColumn('users', 'telefone')) {
                $table->string('telefone')->nullable();
            }
            if (!Schema::hasColumn('users', 'bf')) {
                $table->string('bf')->nullable(); // Benefício
            }
            if (!Schema::hasColumn('users', 'estado_civil')) {
                $table->enum('estado_civil', ['solteiro', 'casado', 'divorciado', 'viuvo', 'uniao_estavel'])->nullable();
            }
            if (!Schema::hasColumn('users', 'tem_filhos')) {
                $table->boolean('tem_filhos')->default(false);
            }
            if (!Schema::hasColumn('users', 'quantidade_filhos')) {
                $table->integer('quantidade_filhos')->nullable();
            }
            
            // Endereço
            if (!Schema::hasColumn('users', 'endereco')) {
                $table->string('endereco')->nullable();
            }
            if (!Schema::hasColumn('users', 'bairro')) {
                $table->string('bairro')->nullable();
            }
            if (!Schema::hasColumn('users', 'cidade')) {
                $table->string('cidade')->nullable();
            }
            if (!Schema::hasColumn('users', 'estado')) {
                $table->string('estado')->nullable();
            }
            if (!Schema::hasColumn('users', 'cep')) {
                $table->string('cep')->nullable();
            }
            
            // Contato de Urgência
            if (!Schema::hasColumn('users', 'urgencia_nome_contato')) {
                $table->string('urgencia_nome_contato')->nullable();
            }
            if (!Schema::hasColumn('users', 'urgencia_telefone_contato')) {
                $table->string('urgencia_telefone_contato')->nullable();
            }
            
            // Informações Físicas
            if (!Schema::hasColumn('users', 'tamanho_camisa')) {
                $table->enum('tamanho_camisa', ['PP', 'P', 'M', 'G', 'GG', 'XG', 'XXG'])->nullable();
            }
            
            // Saúde
            if (!Schema::hasColumn('users', 'tem_deficiencia')) {
                $table->boolean('tem_deficiencia')->default(false);
            }
            if (!Schema::hasColumn('users', 'descricao_deficiencia')) {
                $table->text('descricao_deficiencia')->nullable();
            }
            if (!Schema::hasColumn('users', 'tem_condicao_saude')) {
                $table->boolean('tem_condicao_saude')->default(false);
            }
            if (!Schema::hasColumn('users', 'descricao_saude')) {
                $table->text('descricao_saude')->nullable();
            }
            if (!Schema::hasColumn('users', 'tem_alergia')) {
                $table->boolean('tem_alergia')->default(false);
            }
            if (!Schema::hasColumn('users', 'descricao_alergia')) {
                $table->text('descricao_alergia')->nullable();
            }
            if (!Schema::hasColumn('users', 'usa_medicamento')) {
                $table->boolean('usa_medicamento')->default(false);
            }
            if (!Schema::hasColumn('users', 'qual_medicamento')) {
                $table->text('qual_medicamento')->nullable();
            }
            
            // Educação
            if (!Schema::hasColumn('users', 'ensino_pre_escolar')) {
                $table->boolean('ensino_pre_escolar')->default(false);
            }
            if (!Schema::hasColumn('users', 'ensino_fundamental_concluido')) {
                $table->boolean('ensino_fundamental_concluido')->default(false);
            }
            if (!Schema::hasColumn('users', 'ensino_fundamental_cursando')) {
                $table->boolean('ensino_fundamental_cursando')->default(false);
            }
            if (!Schema::hasColumn('users', 'ensino_fundamental_instituicao')) {
                $table->string('ensino_fundamental_instituicao')->nullable();
            }
            if (!Schema::hasColumn('users', 'ensino_medio_concluido')) {
                $table->boolean('ensino_medio_concluido')->default(false);
            }
            if (!Schema::hasColumn('users', 'ensino_medio_cursando')) {
                $table->boolean('ensino_medio_cursando')->default(false);
            }
            if (!Schema::hasColumn('users', 'ensino_medio_instituicao')) {
                $table->string('ensino_medio_instituicao')->nullable();
            }
            if (!Schema::hasColumn('users', 'ensino_tecnico_concluido')) {
                $table->boolean('ensino_tecnico_concluido')->default(false);
            }
            if (!Schema::hasColumn('users', 'ensino_tecnico_cursando')) {
                $table->boolean('ensino_tecnico_cursando')->default(false);
            }
            if (!Schema::hasColumn('users', 'ensino_tecnico_instituicao')) {
                $table->string('ensino_tecnico_instituicao')->nullable();
            }
            if (!Schema::hasColumn('users', 'ensino_superior_concluido')) {
                $table->boolean('ensino_superior_concluido')->default(false);
            }
            if (!Schema::hasColumn('users', 'superior_cursando')) {
                $table->boolean('superior_cursando')->default(false);
            }
            if (!Schema::hasColumn('users', 'superior_trancado')) {
                $table->boolean('superior_trancado')->default(false);
            }
            if (!Schema::hasColumn('users', 'superior_instituicao')) {
                $table->string('superior_instituicao')->nullable();
            }
            if (!Schema::hasColumn('users', 'pos_graduacao_concluido')) {
                $table->boolean('pos_graduacao_concluido')->default(false);
            }
            if (!Schema::hasColumn('users', 'pos_graduacao_cursando')) {
                $table->boolean('pos_graduacao_cursando')->default(false);
            }
            if (!Schema::hasColumn('users', 'pos_graduacao_instituicao')) {
                $table->string('pos_graduacao_instituicao')->nullable();
            }
            
            // Cursos Extras
            if (!Schema::hasColumn('users', 'curso_1')) {
                $table->string('curso_1')->nullable();
            }
            if (!Schema::hasColumn('users', 'curso_1_instituicao')) {
                $table->string('curso_1_instituicao')->nullable();
            }
            if (!Schema::hasColumn('users', 'curso_2')) {
                $table->string('curso_2')->nullable();
            }
            if (!Schema::hasColumn('users', 'curso_2_instituicao')) {
                $table->string('curso_2_instituicao')->nullable();
            }
            if (!Schema::hasColumn('users', 'curso_3')) {
                $table->string('curso_3')->nullable();
            }
            if (!Schema::hasColumn('users', 'curso_3_instituicao')) {
                $table->string('curso_3_instituicao')->nullable();
            }
            
            // Profissional
            if (!Schema::hasColumn('users', 'situacao_profissional')) {
                $table->enum('situacao_profissional', ['empregado', 'desempregado', 'autonomo', 'estudante', 'aposentado'])->nullable();
            }
            
            // Experiência Profissional 1
            if (!Schema::hasColumn('users', 'experiencia_1_instituicao')) {
                $table->string('experiencia_1_instituicao')->nullable();
            }
            if (!Schema::hasColumn('users', 'experiencia_1_ano')) {
                $table->string('experiencia_1_ano')->nullable();
            }
            if (!Schema::hasColumn('users', 'experiencia_1_funcao')) {
                $table->string('experiencia_1_funcao')->nullable();
            }
            if (!Schema::hasColumn('users', 'experiencia_1_atividades')) {
                $table->text('experiencia_1_atividades')->nullable();
            }
            
            // Experiência Profissional 2
            if (!Schema::hasColumn('users', 'experiencia_2_instituicao')) {
                $table->string('experiencia_2_instituicao')->nullable();
            }
            if (!Schema::hasColumn('users', 'experiencia_2_ano')) {
                $table->string('experiencia_2_ano')->nullable();
            }
            if (!Schema::hasColumn('users', 'experiencia_2_funcao')) {
                $table->string('experiencia_2_funcao')->nullable();
            }
            if (!Schema::hasColumn('users', 'experiencia_2_atividades')) {
                $table->text('experiencia_2_atividades')->nullable();
            }
            
            // Habilidades
            if (!Schema::hasColumn('users', 'nivel_ingles')) {
                $table->enum('nivel_ingles', ['basico', 'avancado', 'fluente'])->nullable();
            }
            if (!Schema::hasColumn('users', 'desenvolveu_sistemas')) {
                $table->boolean('desenvolveu_sistemas')->default(false);
            }
            if (!Schema::hasColumn('users', 'ja_empreendeu')) {
                $table->boolean('ja_empreendeu')->default(false);
            }
            
            // Disponibilidade
            if (!Schema::hasColumn('users', 'disponibilidade_dias')) {
                $table->json('disponibilidade_dias')->nullable(); // Array de dias da semana
            }
            if (!Schema::hasColumn('users', 'disponibilidade_horario')) {
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