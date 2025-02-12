run:
	cp .env.example .env
	composer install
	php artisan migrate
	php artisan serve