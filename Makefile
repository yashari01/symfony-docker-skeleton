# Styles
YELLOW=$(shell echo "\033[00;33m")
RED=$(shell echo "\033[00;31m")
RESTORE=$(shell echo "\033[0m")

.DEFAULT_GOAL := help

# Variables
PHP := php
CURRENT_DIR := $(shell pwd)
APP_DIR := src
APP_INFRA := provisionning
DOCKER_COMPOSE := docker-compose


.PHONY: help
help:
	@echo "*********************"
	@echo "${YELLOW}Available targets${RESTORE}:"
	@echo "*********************"
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-15s[0m %s\n", $$1, $$2}'
	@echo "Docker Compose: ${YELLOW}$(DOCKER_COMPOSE)${RESTORE}"

.PHONY: show_services
show_services: ## show docker containers
	@cd $(APP_INFRA) && docker ps

.PHONY: up
up: ## Up all services
	@cd $(APP_INFRA) && $(DOCKER_COMPOSE) up && clean
	@cd $(APP_DIR) && $(PHP) bin/console doctrine:schema:update --force && $(PHP) bin/console doctrine:fixtures:load

.PHONY: down
down: ## down all services
	@cd $(APP_INFRA) && $(DOCKER_COMPOSE) down

.PHONY: list_injection
list_injection: ## List dependency injection
	@cd $(APP_DIR) && $(PHP) bin/console debug:container --show-hidden

.PHONY: autowiring
autowiring: ## List autowiring services
	@cd $(APP_DIR) && $(PHP) bin/console debug:autowiring


.PHONY: updateschema
updateschema: ## Update the schema
	@cd $(APP_DIR) && $(PHP) bin/console doctrine:schema:update --dump-sql --force
	@cd $(APP_DIR) && $(PHP) bin/console doctrine:migrations:migrate -n

.PHONY: clean
clean: ## Clean cache
	@cd $(APP_DIR) cache:pool:clear cache.global_clearer
