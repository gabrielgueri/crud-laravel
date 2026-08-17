# Laravel + Vue.js Docker Setup

This is a production-ready Docker setup for a Laravel API/SPA application with Vue.js frontend.

## Quick Start

### 1. Prerequisites
- Docker and Docker Compose installed
- An existing Laravel project or create one:
  ```bash
  composer create-project laravel/laravel my-app
  cd my-app
  ```

### 2. Copy Docker Files
Copy the `docker/` directory and `docker-compose.yml` into your Laravel project root.

### 3. Environment Setup
```bash
cp .env.example .env
# Edit .env with your database and app configuration
# Generate APP_KEY if needed:
# php artisan key:generate
```

### 4. Build and Start
```bash
docker compose up --build -d
```

### 5. Run Migrations
```bash
docker compose exec php-fpm php artisan migrate
```

### 6. Install Frontend Assets
```bash
docker compose exec php-fpm npm install
docker compose exec php-fpm npm run build
```

### 7. Access Your App
- Application: http://localhost
- Database: localhost:5432 (PostgreSQL)
- Redis: localhost:6379

## Services

- **nginx** – Web server, serves static assets and proxies PHP requests
- **php-fpm** – Laravel application runtime
- **postgres** – Primary database (PostgreSQL 18)
- **redis** – Cache and queue driver

## Common Commands

```bash
# View logs
docker compose logs -f php-fpm

# Run artisan commands
docker compose exec php-fpm php artisan tinker

# Access database
docker compose exec postgres psql -U laravel -d laravel

# Rebuild containers
docker compose up --build -d

# Stop all services
docker compose down
```

## Notes

- **Assets**: Vue.js assets are built during the Nginx image build. Ensure `npm run build` is configured in `package.json`.
- **Storage**: The `storage/` and `bootstrap/cache/` directories are volumes. They persist across container restarts.
- **Database**: PostgreSQL is the default. To use MySQL, update `docker-compose.yml` and `.env`.
- **Environment Variables**: Configure all settings in `.env` before building.

## Production Considerations

- Use strong database passwords
- Set `APP_DEBUG=false` in production
- Use a managed database service (RDS, managed PostgreSQL) instead of containerized DB
- Implement proper secrets management (Docker Secrets, environment variable services)
- Set up CI/CD pipelines for automated deployments
