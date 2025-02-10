<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Install steps

- run: <code>git clone https://github.com/magerishere/orkide_task.git </code>
- after cloned go to your dir project. run: <code>cd orkide_task</code>
- run: <code>cp .env.example .env</code>
- set any configs need in **.env** like database name, port, host and ...
- run: <code>composer install</code>
- run: <code>php artisan key:generate</code>
- run: <code>php artisan migrate --seed</code>
- for test api use local file postman collection inside project **Orkideh.postman_collection** or download postman
  collection: [download](https://github.com/magerishere/Orkideh.postman_collection)

## Run Test

for running test only run: <code>php artisan test</code>

**NOTE**: test read configs from **.env.testing**, make sure testing database exists.

## Mail Config

I use mailtrap for sending mail, please set your **mailtrap** or other service mail provider before send email testing.
[MailTrap](https://mailtrap.io/)

**NOTE**: I use sync mails for this app, you can use another broadcasting offers laravel like database with jobs and
queue

## Postman config

I use two env variable for postman testing:

- **url** ( api url )
- **user_mobile** ( user mobile for test correct user and change easily )

## Api urls

you can easily find this by run: <code>php artisan route:list</code>

example of api url: **example.test/api/v1/{x}**

## Telescope

this app use telescope laravel for tracking mail, please use **example.test/telescope** for see views.

<br>
**Good luck**
