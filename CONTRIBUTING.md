# Setting up Interclip locally

Hi, thanks for considering contributing to Interclip! Here's a guide on how to get you set up.

## Local development with Docker Compose

Start Docker or Colima (`colima start`), and make sure the Docker CLI and Compose
are available in your shell. Copy `.env.sample` to `.env` if you do not already
have one. Set `PASSWORD`, `REDIS_PASSWORD`, and `IP_HASH_KEY` to separate random
values (for example, generate each with `openssl rand -hex 32`). Keep
`AUTH_TYPE=mock` for initial development.

```sh
docker compose -f compose.local.yaml up -d --build
```

Open <http://localhost:8081>. This local stack builds Apache/PHP 8.5 and frontend
assets, initializes a separate MySQL volume, and starts Redis. Only the web port
is exposed, on the loopback interface. Set `INTERCLIP_PORT` to choose another
port. The existing `docker-compose.yaml` remains a database-only setup.

Source files are copied into the image; rerun the build command after edits.
After changing `.env`, rerun `up -d` to recreate the app with the new settings.
The default mock identity is a visitor. Staff privileges must be assigned
explicitly to its `mock|local-development` subject in the local database.

```sh
# Run the PHP tests inside the app image.
docker compose -f compose.local.yaml exec -T app vendor/bin/pest
# Stop this stack, preserving its database.
docker compose -f compose.local.yaml down
```

### Authentik / OpenID Connect

Create a separate authentik application/provider for local development:

- Confidential OAuth2/OIDC client, with the authorization-code grant only.
- Strict authorization redirect: `http://localhost:8081/login` (adjust for a custom port).
- Scopes: `openid`, `profile`, `email`; include claims in the ID token.
- Select an RSA signing key (RS256), stable UUID subjects, and a provider-specific issuer.
- Bind access to the intended test users and use an explicit-consent authorization flow.

In `.env`, set `AUTH_TYPE=account`, `OIDC_CLIENT_ID`, `OIDC_CLIENT_SECRET`, and the exact `OIDC_ISSUER` from discovery,
including its trailing slash. Recreate the app with `docker compose -f compose.local.yaml up -d app`, then visit
`http://localhost:8081/login`.

OIDC requires HTTPS discovery/endpoints, S256 PKCE, and signed ID tokens. Browser
login attempts expire after 10 minutes. The local session expires when the ID
token does, capped at one hour; tokens are not stored in browser cookies.
Logout clears only the Interclip session, preserving the shared authentik login.

OIDC accounts are keyed by `oidc|` plus SHA-256 of the PHP JSON encoding of
`[issuer, sub]` (see `oidcAccountSubject()` in `includes/lib/oidc.php`). Email is
profile data, not an account-linking key: an unverified email cannot claim an
existing account or staff role. New SSO identities are visitors. After verifying
the person and their issuer/subject, an administrator can explicitly migrate the
existing account's subject by primary key. Never grant staff based on an email
match or SSO group claims. Existing accounts must be explicitly migrated to their OIDC subjects.

To return to local mock login, set `AUTH_TYPE=mock` and recreate the app.

## Prerequisites

- Apache server (needed for the .htaccess redirections)
- PHP 8.2+
- MySQL database server
- Composer v2 or newer
- Git
- Sass CLI
- PostCSS (with CLI)
- Autoprefixer
- Redis ([installation guide](https://redis.io/download))

## Installing dependencies

1. `composer install`
2. (optional) Install the Redis PHP extension: `sudo apt-get install php-redis`
3. (optional) After downloading the extension, add this to your php.ini: `extension=redis.so` (you may need to restart your Apache server afterwards)

## Compiling stylesheets

1. Install sass globally: `npm i -g sass`
2. Watch the styles `sass scss/:css/ -w -s compressed`

## Setting up the env files

1. First things first, copy the example .env file called `.env.sample`:

```
cp .env.sample .env
```

2. Change the values to your environment, most importantly
   - DB credentials, like the server, username, and password of your MySQL database
   - The environment, like staging, development, or production
   - The cryptographic salt used for hashing IP addresses
   - OIDC issuer and client credentials (required for account login)
   - Sentry credentials (optional)
   - Rclone config

## Setting up the database

You can create the database and the tables by just executing the following SQL query:

```sql

USE iclip;

/* Clips table */
DROP TABLE IF EXISTS `userurl`;
CREATE TABLE `userurl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usr` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expires` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* Accounts table */
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `email` varchar(128) NOT NULL,
  `role` varchar(64) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0;

/* Add the mocked admin account as an admin on the site */
INSERT INTO accounts VALUES('admin@example.org', 'staff', NULL);

```

### Setting up MySQL cron jobs

```sql
/* Delete expired clips (runs every hour) */
CREATE EVENT `clean_expired` ON SCHEDULE EVERY 1 HOUR STARTS '2021-02-01 13:39:14' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM userurl WHERE expires < CURDATE();

/* Cleans the DB hits table with events older than 5 hours */
CREATE EVENT `clean_hits` ON SCHEDULE EVERY 1 DAY STARTS '2014-01-18 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM `hits` where `date` < (CURRENT_TIMESTAMP - 18000);
```
