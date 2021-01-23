user := $(shell id -u)
group := $(shell id -g)
dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
de := $(dc) exec

.DEFAULT_GOAL := help
.PHONY: help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: up
up: ## Lance les conteneurs
	$(dc) up -d

.PHONY: build
build: ## Construit les conteneurs
	$(dc) build

.PHONY: dep
dep: ## Install les dépendances PHP
	$(de) php composer install

.PHONY: init
init: build up dep ## Initialise et lance les conteneurs

.PHONY: stop
stop: ## Stop les conteneurs
	$(dc) stop

.PHONY: clean
clean: ## Stop et supprime les conteneurs
	$(dc) down -v --remove-orphans
