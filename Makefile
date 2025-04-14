up:
	docker compose up -d --build

down:
	docker compose down

logs:
	docker compose logs -f

bash:
	docker compose exec php bash

install:
	docker compose exec php composer install

console:
	docker compose exec php php bin/console $(args)

dbshell:
	docker compose exec db mysql -usymfony -psymfony app
