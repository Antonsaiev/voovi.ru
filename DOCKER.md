# Docker dev/prod

Проект запускается в Docker, потому что приложение использует PHP 5.6 и старое расширение `mysql_*`.

## Локальная разработка

Локальное окружение описано в `docker-compose.yml` и использует `.env.dev`.

Сервисы:

- `app` — PHP 5.6 + Apache, код примонтирован из текущей папки.
- `db` — MariaDB 10.2.
- `phpmyadmin` — доступен локально.

Запуск:

```bash
./dev.sh
```

То же самое явно:

```bash
./dev.sh up
```

Адреса по умолчанию:

- приложение: `http://localhost`
- phpMyAdmin: `http://localhost:8081`

Так как код примонтирован в контейнер через bind mount, после правок PHP/JS/CSS не нужно делать `docker compose cp`.

В Docker `.htaccess` закрывает прямую отдачу `.env*`, `.git`, `*.sql` и служебных Docker-файлов из web root.

## Локальный restore БД

Restore всегда полный и без бэкапа. Перед импортом локальная база удаляется и создается заново.

```bash
./dev.sh voovi.sql
```

Скрипт:

- поднимает локальные контейнеры;
- выполняет `DROP DATABASE IF EXISTS`;
- создает базу `voovi` в `utf8`;
- импортирует указанный SQL-файл.

Если dump-файл не найден, restore не запускается.

## Production

Продакшен запускается через `docker-compose.prod.yml` и `.env.prod`.

На сервере:

```bash
git pull --ff-only
docker compose -f docker-compose.prod.yml up -d --build
```

В продакшене compose поднимает только `app`. База данных внешняя и задается в `.env.prod` через:

- `DB_HOST`
- `DB_LOGIN`
- `DB_PASSWORD`
- `DB_NAME`

Контейнер слушает только `127.0.0.1:8080`, а публичный HTTPS и домен обслуживает внешний прокси. Если нужен другой локальный порт для прокси, поменяйте строку `ports` в `docker-compose.prod.yml`.

Если MySQL находится на этом же VPS вне Docker, можно оставить:

```dotenv
DB_HOST=host.docker.internal
```

Для этого в `docker-compose.prod.yml` добавлен `host.docker.internal:host-gateway`.

## Env-файлы

`.env.dev` и `.env.prod` хранятся в приватном репозитории.

Они исключены из Docker build context через `.dockerignore`, поэтому не попадают внутрь Docker image при `COPY .`.

## Runtime-директории

В production persistent volumes подключены для директорий, куда приложение пишет файлы:

- `doc`
- `log`
- `upload`
- `files`
- `voicecatalog`
- `img`
- `vipiska`
- `scheta`
- `mail/database`

## Проверка конфигурации

Локально:

```bash
docker compose --env-file .env.dev config
```

Прод:

```bash
docker compose -f docker-compose.prod.yml config
```
