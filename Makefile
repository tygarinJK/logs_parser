install: \
	build \
	up \
	composer-install \
	dbupdate \
	run-migrations

build:
	@echo "Building the docker containers..."
	cp ./docker/.env.example ./docker/.env
	cd ./docker && docker compose build

up:
	cd ./docker && docker compose up -d

composer-install:
	@echo "Installing composer dependencies..."
	cp ./app/.env.local.example ./app/.env
	cd ./docker && docker compose exec php composer install

dbupdate:
	cd ./docker && docker compose exec php php bin/console doctrine:database:create --if-not-exists

run-migrations:
	cd ./docker && docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

down:
	cd ./docker && docker compose down

import:
	cd ./docker && docker compose exec php php bin/console app:import-logs /logs/logs.log 10

test:
	cd ./docker && docker compose exec php composer test

clean:
	cd ./docker && docker compose down -v
	git clean -fdx -e .idea

sh:
	cd ./docker && docker compose exec -u 1000:1000  php bash -l
