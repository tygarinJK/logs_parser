up:
	cd ./docker && docker compose up -d --build

down:
	cd ./docker && docker compose down

logs:
	cd ./docker && docker compose logs -f

bash:
	cd ./docker && docker compose exec php bash

install:
	cd ./docker && docker compose exec php composer install

console:
	cd ./docker && docker compose exec php php bin/console $(args)

dbshell:
	cd ./docker && docker compose exec db mysql -usymfony -psymfony app

import:
	cd ./docker && docker compose exec php php bin/console app:import-logs /logs/logs.log 10
