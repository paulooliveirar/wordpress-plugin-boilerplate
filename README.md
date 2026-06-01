# Repo skeleton for creating WordPress plugins using Docker

Local environment for WordPress plugin development. Only `wp-content` is versioned; core and WP-CLI live in a custom Docker image built from this repo.

## Requirements

- Docker
- Docker Compose
- Make

## Quick start

```sh
chmod +x install.sh && ./install.sh
```

Or step by step:

```sh
make setup      # .env, build image, start stack
make wp-install # install WordPress in MySQL via WP-CLI
```

Open [http://localhost:8000](http://localhost:8000) — admin credentials are in `.env` (`WP_ADMIN_USER` / `WP_ADMIN_PASSWORD`).

Plugin and theme updates use the filesystem directly (`FS_METHOD=direct`); WordPress will not ask for FTP credentials.

## Makefile commands

```sh
make help           # list all targets
make build          # build image (downloads WordPress core + WP-CLI)
make up / make down # start / stop
make wp CMD="..."   # run any WP-CLI command
make plugin-list
make plugin-activate PLUGIN=example-plugin
make db-export
make db-import FILE=backups/db.sql
make core-rebuild   # rebuild image with latest core
make shell          # bash inside WordPress container
```

Examples:

```sh
make wp CMD="plugin list"
make wp CMD="cache flush"
make wp CMD="user list"
```

## Project layout

| Path | Role |
|------|------|
| `wp-content/plugins/` | Your plugins (versioned) |
| `wp-content/themes/` | Your themes (versioned) |
| `Dockerfile` | WordPress core download at build + WP-CLI |
| `Makefile` | Common dev commands |

WordPress core (`wp-admin`, `wp-includes`, …) exists only inside the container image, not in Git.

## Updating WordPress core

Rebuild the image (downloads core again at build time):

```sh
make core-rebuild
```

Pin a version in `.env`: `WP_VERSION=6.7.2`, then `make build`.

## WP-CLI

WP-CLI is installed at `/usr/local/bin/wp` in the container. Use `make wp CMD="..."` or:

```sh
docker compose exec wordpress wp --allow-root plugin list
```
