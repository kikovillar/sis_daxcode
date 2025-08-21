# Sistema de Avaliação Online Dax Code para 2025 

Sistema completo de avaliações online desenvolvido em Laravel 12 com Blade, Livewire e Tailwind CSS.

## Funcionalidades

### 1. Autenticação e Perfis
- Sistema de login com roles (admin, professor, aluno)
- Painel administrativo para gestão de usuários

### 2. Módulo de Avaliações
- CRUD completo para avaliações
- Controle de tempo e status
- Relação com turmas e alunos

### 3. Sistema de Questões
- Banco de questões categorizadas
- Múltiplos tipos: múltipla escolha, V/F, dissertativa
- Interface drag-and-drop

### 4. Teste Paginado
- Layout responsivo com timer
- Auto-salvamento
- Bloqueio por tempo

### 5. Correção e Notas
- Cálculo automático
- Correção manual
- Exportação de resultados

### 6. Relatórios
- Análise de desempenho
- Estatísticas detalhadas

## Instalação

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
```

## Tecnologias

- Laravel 12
- Livewire 3
- Tailwind CSS
- MySQL
- Alpine.js
