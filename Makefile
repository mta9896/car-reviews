up:
	docker-compose up -d

destroy:
	docker-compose down

test:
	docker-compose exec symfony ./vendor/bin/phpunit

init:
	docker-compose exec symfony composer install
	docker-compose exec symfony bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec symfony bin/console doctrine:fixtures:load --no-interaction

fixture:
	docker-compose exec symfony bin/console doctrine:fixtures:load --no-interaction