start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch tests/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm install

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	touch tests/database.sqlite
	php artisan test

test-coverage:
	touch tests/database.sqlite
	composer exec --verbose phpunit tests -- --coverage-clover clover.xml --verbose

deploy:
	git push heroku

lint:
	composer run-script phpcs

lint-fix:
	composer phpcbf
