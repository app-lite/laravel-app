up: docker-up
init: docker-build docker-up

docker-up:
	docker-compose up -d

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-build:
	docker-compose build
