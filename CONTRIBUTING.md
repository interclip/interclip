# Setting up Interclip locally

Hi, thanks for considering contributing to Interclip! Here's a guide on how to get you set up.

## Recommended: Dev Container

The repository includes a Dev Container that runs Apache with PHP 8.2, MySQL,
and password-protected Redis as separate Compose services. It installs PHP and
JavaScript dependencies, builds the ignored frontend assets, initializes a
project-scoped database, and runs an end-to-end smoke test on first creation.

1. Install Docker and a client that supports the
   [Dev Container specification](https://containers.dev/).
2. Open the repository and choose **Reopen in Container**.
3. Open [http://localhost:8080](http://localhost:8080).

MySQL and Redis are reachable by the `mysql` and `redis` service names from the
app container. They are not published to the host. Only the app is bound to the
host, on loopback port 8080.

Run the complete check again at any time from inside the container:

```sh
bash scripts/dev-smoke.sh
```

The smoke test creates and cleans up clips through both the JSON API and the
CSRF-protected browser forms. It also verifies MySQL fallback, Redis cache
repopulation, exact 48-hour expiry, reusable short codes, safe
handling of non-navigable URIs, direct redirects, mock account provisioning,
and visitor access control.

To reset all local database and Redis data, close the Dev Container and run the
following from the host. This intentionally deletes only this Compose project's
development volumes:

```sh
docker compose --file .devcontainer/compose.yaml down --volumes
```

### Optional Auth0 login

Mock authentication is the deterministic default. It provisions
`mock|local-development` as a visitor and allows authorization behavior to be
tested without external credentials.

To test a developer-owned Auth0 tenant, copy
`.devcontainer/local.env.example` to `.devcontainer/local.env`, fill in the
credentials, and rebuild the Dev Container. Configure these tenant URLs:

- callback: `http://localhost:8080/login`
- logout return: `http://localhost:8080/`

The local override file is ignored by Git. Real Auth0 login is not exercised by
the automated smoke test because it depends on an external tenant and an
interactive identity flow.

## Prerequisites

- Apache server (needed for the .htaccess redirections)
- PHP 8.2+
- Node.js 20.19+
- MySQL database server
- Composer v2 or newer
- Git
- Redis ([installation guide](https://redis.io/download))
- PHP extensions: cURL, MySQLi, and Redis

## Installing dependencies

1. `composer install`
2. `yarn install --frozen-lockfile`
3. Install the required PHP extensions, for example: `sudo apt-get install php-curl php-mysql php-redis`

## Compiling stylesheets

1. Build scripts and styles with `yarn build`.
2. Watch styles with `yarn compile:styles --watch`.

## Setting up the env files

1. First things first, copy the example .env file called `.env.sample`:

```
cp .env.sample .env
```

2. Change the values to your environment, most importantly
   - DB credentials, like the server, username, and password of your MySQL database
   - The environment, like staging, development, or production
   - Random Redis, IP hashing, and Auth0 cookie secrets
   - `APP_URL`, trusted proxies, and the production fail-closed rate limit
   - Auth0 credentials (optional)
   - Sentry credentials (optional)
   - Rclone config

Infrastructure-only credentials such as `MYSQL_ROOT_PASSWORD` and phpMyAdmin credentials must not be placed in the application `.env`. Supply them to Docker Compose from a separate, access-restricted environment file or secret store.

## Setting up the database

For a new local database, import the maintained schema:

```sh
mysql iclip < scripts/db.sql
```

Existing installations must apply the SQL files in `scripts/migrations/` in order. Review and back up the database before applying a migration.
