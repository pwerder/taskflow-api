# TaskFlow API

API REST de gerenciamento de tarefas construída com Laravel.

## Stack
- Laravel
- Sanctum
- MySQL
- PHPUnit

## Funcionalidades
- Autenticação
- CRUD de tarefas
- Filtros
- Paginação
- Ordenação
- Teste automatizados

## Como Rodar
```
composer install
```

```
cp .env.example .env
```

```
php artisan key:generate
```

```
php artisan migrate
```

```
php artisan serve
```

## Rotas Principais
| Método | Rota |
| :--- | :--- |
| `POST` | `/api/auth/register` |
| `POST` | `/api/auth/login` |
| `POST` | `/api/v1/tasks` |
