#!make
.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

composer-install: ## composer install
	docker run --rm -it  -w="/srv/app" --volume $$(pwd)/.config/composer:/root/.config/composer --volume $${SSH_AUTH_SOCK}:/ssh-agent --env SSH_AUTH_SOCK=/ssh-agent --volume $$(pwd):/srv/app composer:2.9.2 install

composer-update: ## composer update
	docker run --rm -it  -w="/srv/app" --volume $$(pwd)/.config/composer:/root/.config/composer --volume $${SSH_AUTH_SOCK}:/ssh-agent --env SSH_AUTH_SOCK=/ssh-agent --volume $$(pwd):/srv/app composer:2.9.2 update

phpunit: ## phpunit
	docker run --rm -it  -w="/srv/app" --volume $$(pwd)/.config/composer:/root/.config/composer --volume $${SSH_AUTH_SOCK}:/ssh-agent --env SSH_AUTH_SOCK=/ssh-agent --volume $$(pwd):/srv/app --entrypoint="" composer:2.9.2 vendor/bin/phpunit
