# TaskFlow API

API REST de gerenciamento de tarefas construída com Laravel.

## Stack
- PHP 8.4
- Laravel 13
- MySQL
- Redis
- Docker
- Nginx
- GitHub Actions

## Funcionalidades
- Autenticação de usuários
- CRUD de tarefas
- Filtros e ordenação
- Paginação
- Ownership validation
- Cache com Redis
- Queue/Jobs com Redis
- Docker + Nginx
- CI/CD com GitHub Actions
- Teste automatizados

## Arquitetura

- Laravel API REST
- PHP-FPM
- Nginx reverse proxy
- Redis para cache e filas
- MySQL para persistência

## Como Rodar
```
git clone ...
cd taskflow-api

cp .env.example .env

docker compose up -d --build

docker compose exec app composer install

docker compose exec app php artisan key:generate

docker compose exec app php artisan migrate
```

## Rotas Principais
| Método | Rota |
| :--- | :--- |
| `POST` | `/api/auth/register` |
| `POST` | `/api/auth/login` |
| `POST` | `/api/v1/tasks` |
