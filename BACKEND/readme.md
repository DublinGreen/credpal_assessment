# corporate setup documentation (Lumen PHP Framework)

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# PROJECT TECHNOLOGIES

FRONTEND WITH VUE JS
BACKEND WITH LUMEN
DATABASE MYSQL

## COMMONLY USED COMMANDS, LINKS

## php -S localhost:9000 -t public

## php -S corporatesetup.app:9000 -t public

## php -S 134.209.18.95:9000 -t public

## ALTER TABLE `users_documents`

## ADD CONSTRAINT `user_document_to_company_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

## https://httpstatuses.com/

## php artisan swagger-lume:generate

## php artisan swagger-lume:publish

## php artisan swagger-lume:

## Used php 7.2 to develop

## apt-get install php7.2-xml (install php7.2-xml)

## need php 7.3 to run test

## vendor/bin/phpunit

before_script:

- mysql -u root -e 'CREATE DATABASE testbase;'
- mysql -u root testbase < tests/testbase.sql

## access swagger page http://localhost:9000/api/documentation

## php -S localhost:9000 public/index.php

## If you're already running the SQL shell, you can use the source command to import data:

## use databasename;

## source data.sql;

## https://gitmemory.com/issue/DarkaOnLine/SwaggerLume/71/488768241

## https://medium.com/@garrettvorce/getting-started-with-laravel-and-swagger-b14c66f35576

## ALTER TABLE Persons ADD UNIQUE (ID);

## https://www.digitalocean.com/community/questions/how-keep-my-app-running-after-close-putty-f82aab17-ca84-46a0-8a39-3e25f1dd2d45
