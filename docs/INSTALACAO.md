# Guia de Instalação - Sistema de Avaliação DaxCode

## Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18+ e NPM
- MySQL 8.0 ou superior
- Git

## Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/sistema-avaliacao.git
cd sistema-avaliacao
```

### 2. Instale as dependências PHP

```bash
composer install
```

### 3. Instale as dependências JavaScript

```bash
npm install
```

### 4. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure o banco de dados

Edite o arquivo `.env` com suas configurações de banco:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_avaliacao
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 6. Execute as migrations e seeders

```bash
php artisan migrate --seed
```

### 7. Configure as permissões (se necessário)

```bash
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Ou para desenvolvimento local
chmod -R 777 storage bootstrap/cache
```

### 8. Compile os assets

```bash
# Para desenvolvimento
npm run dev

# Para produção
npm run build
```

### 9. Inicie o servidor

```bash
php artisan serve
```

O sistema estará disponível em `http://localhost:8000`

## Configurações Adicionais

### Filas (Recomendado para produção)

Configure o driver de filas no `.env`:

```env
QUEUE_CONNECTION=database
```

Execute o worker de filas:

```bash
php artisan queue:work
```

### Cache (Produção)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Scheduler (Produção)

Adicione ao crontab:

```bash
* * * * * cd /caminho/para/projeto && php artisan schedule:run >> /dev/null 2>&1
```

## Usuários Padrão

Após executar os seeders, você terá acesso aos seguintes usuários:

### Administrador
- **Email:** admin@sistema.com
- **Senha:** password

### Professor
- **Email:** joao@sistema.com
- **Senha:** password

### Aluno
- **Email:** aluno1@sistema.com
- **Senha:** password

## Configurações de Segurança

### 1. Configurar HTTPS (Produção)

No `.env`:

```env
APP_URL=https://seudominio.com
FORCE_HTTPS=true
```

### 2. Configurar CORS

Edite `config/cors.php` conforme necessário.

### 3. Configurar Rate Limiting

As rotas já possuem rate limiting configurado. Ajuste em `app/Http/Kernel.php` se necessário.

## Backup

### Configurar backup automático

```bash
composer require spatie/laravel-backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

Configure no `config/backup.php` e adicione ao scheduler.

## Monitoramento

### Logs

Os logs ficam em `storage/logs/laravel.log`

### Performance

Para monitoramento de performance, considere:
- Laravel Telescope (desenvolvimento)
- New Relic ou similar (produção)

## Troubleshooting

### Erro de permissões

```bash
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 777 storage bootstrap/cache
```

### Erro de memória

No `.env`:

```env
MEMORY_LIMIT=512M
```

### Problemas com Livewire

```bash
php artisan livewire:publish --config
php artisan view:clear
```

### Problemas com assets

```bash
npm run build
php artisan view:clear
```

## Estrutura de Pastas

```
sistema-avaliacao/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Livewire/            # Componentes Livewire
│   ├── Models/              # Modelos Eloquent
│   └── Policies/            # Políticas de autorização
├── database/
│   ├── migrations/          # Migrations do banco
│   └── seeders/            # Seeders
├── resources/
│   ├── views/              # Views Blade
│   └── js/                 # JavaScript/Alpine.js
├── routes/                 # Definições de rotas
└── docs/                   # Documentação
```

## Comandos Úteis

```bash
# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar banco (CUIDADO!)
php artisan migrate:fresh --seed

# Verificar status das filas
php artisan queue:monitor

# Executar testes
php artisan test
```

## Suporte

Para suporte técnico:
- Verifique os logs em `storage/logs/`
- Consulte a documentação do Laravel
- Abra uma issue no repositório do projeto