up: docker-up
init: docker-down-clear docker-build docker-up docker-composer-install env-generate docker-migrate docker-fixtures docker-assets-install docker-assets-dev
test: docker-test

docker-up:
	docker-compose up -d

docker-down-clear:
	docker-compose down -v --remove-orphans

env-generate:
	cp .env.example .env
	docker-compose run --rm dev-laravel-php-cli php artisan key:generate

docker-build:
	docker-compose build

docker-composer-install:
	docker-compose run --rm dev-laravel-php-cli composer install

docker-migrate:
	docker-compose run --rm dev-laravel-php-cli php artisan migrate

docker-fixtures:
	docker-compose run --rm dev-laravel-php-cli php artisan db:seed

docker-assets-install:
	docker-compose run --rm dev-laravel-node yarn install

docker-assets-dev:
	docker-compose run --rm dev-laravel-node yarn run dev

docker-test:
	docker-compose run --rm dev-laravel-php-cli php vendor/bin/phpunit --testdox
