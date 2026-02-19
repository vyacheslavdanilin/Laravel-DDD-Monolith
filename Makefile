.PHONY: lint lint-fix static test ci install

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
