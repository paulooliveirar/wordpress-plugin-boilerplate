.PHONY: help setup build up down restart logs shell wp wp-install permissions \
	plugin-list plugin-activate db-export db-import core-version wait-db clean

COMPOSE  ?= docker compose
SERVICE  ?= wordpress
CONTAINER ?= wordpress
WP_CLI   = $(COMPOSE) exec -T $(SERVICE) wp --allow-root

-include .env
export

WP_URL              ?= http://localhost:8000
WP_TITLE            ?= WordPress Dev
WP_ADMIN_USER       ?= admin
WP_ADMIN_PASSWORD   ?= admin
WP_ADMIN_EMAIL      ?= admin@example.com

help: ## List available commands
	@grep -E '^[a-zA-Z0-9_.-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-18s\033[0m %s\n", $$1, $$2}'

.env:
	@test -f .env || cp .env.sample .env

setup: .env build up wait-db permissions wp-fs-direct ## First-time setup (build, start, permissions)
	@echo ""
	@echo "Stack ready. Run 'make wp-install' to install WordPress in the database."
	@echo "Site URL: $(WP_URL)"

build: ## Build image (WordPress core + WP-CLI)
	$(COMPOSE) build --pull

up: ## Start containers
	$(COMPOSE) up -d

down: ## Stop containers
	$(COMPOSE) down

restart: down up ## Restart containers

logs: ## Follow WordPress logs
	$(COMPOSE) logs -f $(SERVICE)

shell: ## Open bash in WordPress container
	$(COMPOSE) exec $(SERVICE) bash

wait-db: ## Wait until MySQL is ready
	@echo "Waiting for database..."
	@until $(COMPOSE) exec -T db mysqladmin ping -h localhost -u"$(WORDPRESS_DB_USER)" -p"$(WORDPRESS_DB_PASSWORD)" --silent 2>/dev/null; do sleep 2; done
	@echo "Database is ready."

permissions: ## Fix wp-content ownership (required for plugin/theme installs)
	$(COMPOSE) exec -T $(SERVICE) bash -c '\
		mkdir -p /var/www/html/wp-content/{upgrade,uploads,cache,plugins,themes} && \
		chown -R www-data:www-data /var/www/html/wp-content && \
		find /var/www/html/wp-content -type d -exec chmod 775 {} \; && \
		find /var/www/html/wp-content -type f -exec chmod 664 {} \;'
	@echo "wp-content is writable by www-data (upgrade, uploads, plugins)."

wp: ## Run WP-CLI (e.g. make wp CMD="plugin list")
	@test -n "$(CMD)" || (echo "Usage: make wp CMD=\"plugin list\"" && exit 1)
	$(WP_CLI) $(CMD)

wp-install: wait-db wp-fs-direct ## Install WordPress (core + database)
	@$(WP_CLI) core is-installed >/dev/null 2>&1 && echo "WordPress already installed." && exit 0 || true
	$(WP_CLI) core install \
		--url="$(WP_URL)" \
		--title="$(WP_TITLE)" \
		--admin_user="$(WP_ADMIN_USER)" \
		--admin_password="$(WP_ADMIN_PASSWORD)" \
		--admin_email="$(WP_ADMIN_EMAIL)" \
		--skip-email
	@echo "Installed. Admin: $(WP_ADMIN_USER) / $(WP_ADMIN_PASSWORD)"

wp-fs-direct: ## Use direct filesystem (no FTP prompt for updates)
	@$(WP_CLI) config get FS_METHOD 2>/dev/null | grep -q '^direct$$' && exit 0 || \
		$(WP_CLI) config set FS_METHOD direct --type=constant

plugin-list: ## List plugins
	$(WP_CLI) plugin list

plugin-activate: ## Activate plugin (PLUGIN=slug)
	@test -n "$(PLUGIN)" || (echo "Usage: make plugin-activate PLUGIN=example-plugin" && exit 1)
	$(WP_CLI) plugin activate "$(PLUGIN)"

db-export: ## Export database to backups/
	@mkdir -p backups
	$(WP_CLI) db export - --add-drop-table > "backups/db-$$(date +%Y%m%d%H%M%S).sql"
	@echo "Exported to backups/"

db-import: ## Import database (FILE=backups/db.sql)
	@test -n "$(FILE)" || (echo "Usage: make db-import FILE=backups/db.sql" && exit 1)
	@test -f "$(FILE)" || (echo "File not found: $(FILE)" && exit 1)
	cat "$(FILE)" | $(COMPOSE) exec -T $(SERVICE) wp db import - --allow-root

core-version: ## Show WordPress version in container
	$(WP_CLI) core version

core-rebuild: ## Rebuild image with latest WordPress core
	$(COMPOSE) build --no-cache --pull $(SERVICE)
	$(COMPOSE) up -d

clean: down ## Stop and remove containers (keeps db volume)
	@echo "Database volume preserved. Use 'docker volume rm ...' to remove db_data."
