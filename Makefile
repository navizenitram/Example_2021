#!/bin/bash

current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
SHELL = /bin/sh

OS := $(shell uname)
CONTAINER = php74
USER = application

ACTION := $(ACTION '')

args = `arg="$(filter-out $@,$(MAKECMDGOALS))" && echo $${arg:-${1}}`

ifeq ($(OS),Darwin)
	UID = $(shell id -u)
else ifeq ($(OS),Linux)
	UID = $(shell id -u)
else
	UID = 1000
endif

##HELP
.PHONY: help
help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':##'

# 🐳 Docker Compose
.PHONY: build
build: ## Rebuilds all the containers
ifeq ($(OS),Darwin)
	U_ID=${UID} docker-compose -f docker-compose.yml build --compress --parallel
else
	U_ID=${UID} docker-compose -f docker-compose.yml build
endif

.PHONY: up
up: ## Start the containers
	@ U_ID=${UID} docker-compose -f docker-compose.yml up -d --force-recreate

.PHONY: stop
stop: ## Stop the containers
	@ U_ID=${UID} docker-compose -f docker-compose.yml stop

.PHONY: down
down: ## Down the containers
	@ U_ID=${UID} docker-compose -f docker-compose.yml down

.PHONY: build-up
build-up: ## Build && Start the containers
	@ $(MAKE) build && $(MAKE) up

.PHONY: restart
restart: ## Restart the containers
	@ $(MAKE) stop && $(MAKE) up

.PHONY: composer-install
composer-install: ## Installs composer dependencies
	@ docker exec -t -u ${USER} ${CONTAINER} sh -c 'cd /var/www/ && composer install'

.PHONY: ssh
ssh: ## ssh's into the be container
	@ docker exec -it -u ${USER} ${CONTAINER} bash

