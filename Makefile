.PHONY: lint lint-fix static test ci install composer-lock

# Regenerate composer.lock in PHP 8.2 (matches CI). Commit the result.
composer-lock:
	docker run --rm -v "$(CURDIR):/app" -w /app davidzapata/php-composer-alpine:8.2 composer update --no-interaction --no-scripts --prefer-dist

lint:
	composer lint

lint-fix:
	composer lint:fix

static:
	composer static

test:
	composer test

ci: lint static test

install:
	composer install
