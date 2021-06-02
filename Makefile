.DEFAULT_GOAL := help
.PHONY: help run stop

help:
	@echo ''
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
	@echo ''

KEYS := build exec
APP_NAME ?= "birthday_reminder"

define LOOPBODY
  ifneq ($$(filter $$(KEYS),$(v)),)
    RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    $(eval $(RUN_ARGS):;@:)
  endif
endef

$(foreach v,$(firstword $(MAKECMDGOALS)),$(eval $(LOOPBODY)))

## Commands:
start: ## - Starting all docker containers
	@test -s .env || cp .env.example .env
	@test -s docker/.env || cp docker/.env.example docker/.env
	docker-compose -p $(APP_NAME) -f docker/docker-compose.yml up -d --build --force-recreate

down: ## - Stop all docker containers
	docker-compose -p $(APP_NAME) -f docker/docker-compose.yml down

exec: ## - Exec some container e.g. make exec backend sh
	docker-compose -p $(APP_NAME) -f docker/docker-compose.yml exec $(RUN_ARGS)
run-tests: ## - Runs tests inside backend container
	docker-compose -p $(APP_NAME) -f docker/docker-compose.yml exec backend bash -c "composer tests"
%:
@: