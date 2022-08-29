db_dev:
	docker run --rm -it \
	-e POSTGRES_USER=eastnetic \
	-e POSTGRES_PASSWORD=secret \
	-e POSTGRES_DB=eastnetic \
	-p 5432:5432 postgres:latest

redis_dev:
	docker run --rm -it \
	-p 6379:6379 redis:latest

dev:
	php artisan serve

test:
	php artisan test

migrate:
	php artisan migrate

seed:
	php artisan db:seed

.PHONY: db_dev, dev, redis_dev, test, migrate, seed

