# Time Tracking API

### Installation

Time tracking API is made with Laravel 8.
Time Tracking API requires:

* PHP >= 7.3
* Composer
* BCMath PHP Extension
* Ctype PHP Extension
* Fileinfo PHP Extension
* JSON PHP Extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PDO PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension

```sh
$ cd time_tracking_api
$ composer install
$ cp .env.example .env
```
Go into the .env file and fill it with your database connection info

```sh
$ php artisan key:generate
$ php artisan:migrate
$ php artisan serve
```

If you would like to seed database you can run:
```sh
$ php artisan db:seed
```
all the passwords for generated users would be 'password'.
To run tests if the API is working properly you can run:
```sh
$ vendor/bin/phpunit
```