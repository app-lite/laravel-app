up: docker-up
init: docker-down-clear docker-build docker-up
test: docker-test

docker-up:
	docker-compose up -d

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-build:
	docker-compose build

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
