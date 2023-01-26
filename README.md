# Instalation instructions

It is assumed that you have globaly installed PHP 8.x, Composer and Docker. Be sure that you followed instruction stated in [Laravel Getting Started](https://laravel.com/docs/9.x#laravel-and-dockerClone) the project into desired folder

```
git clone https://github.com/krdr-mft/menu-exercise.git
```

Sail is already installed in project therefore, it is enough to run
```
./vendor/bin/sail up
```

If you choose other method of running application, make sure that name of database shold be **laravel**

## Database

1. To create database structer run `php artisan migrate` or `sail artisan migrate`
2. Seed database with `php artisan db:seed` or `sail artisan db:seed`
3. If needed, database state can be refreshed with `./vendor/bin/sail artisan migrate:refresh --seed`

## Running
Application will be servered on localhost:8081. Calling `http://localhost:8081` in browser will open currency purchasing page, as per request.Example call API: http://localhost:8081//api/currencies

## REST API

`GET /api/currencies` list of avaliable currencies

`GET /api/currencies/rates/refreshby/{ISOcode}` refreshes exchange rates with data from external service. {ISOcode} is 3 character ISO code of base currency (USD)

`GET /api/currencies/rates/{ISOcode}` retrieves stored exchange rates for base currency (USD)

`GET /api/orders` list of all orders made

`POST /api/order` creates new order. Expects DTO of following structure {"buy":<iso code>,"amount": <amount>, "for": <iso code>}. In Postman, it Body should be sent as "raw" and of "JSON" type. Example: {"buy":"GBP","amount":100, "for": "USD"}

`GET /api/order/actions`  list of order actions, actions executed when order is created (allow discount for EUR, send mail for GBP)
Example of result:
```
{
    "data": [
        {
            "currency": "EUR",
            "action": "discount",
            "parameter": "percentage",
            "value": "2"
        },
        {
            "currency": "GBP",
            "action": "sendmail",
            "parameter": "mail",
            "value": "menutest@yopmail.com"
        }
    ]
}
    
```
`GET /api/order/action/types` list of action types (discount, sendmail)
    
`PATCH /api/order/action/{action}/for/{currency}` Updates action, where {action} is name of action (discount or sendmail) and {currency} denotes on whic currency action relates. Example:

`PATCH /api/order/action/discount/for/EUR` and with body `{parameter: 5}` will set 5% discount on purchasing EUR.

## Troubleshooting
If sail wont start beacuse of port collison, change ports stated in APP_PORT and DB_PORT parameters, then refresh configuration.
---
To avoid problems with storing session data, wirting rights should be set on <project_root>/storage folder:

```
chmod -R gu+w storage
chmod -R guo+w storage
php artisan optimize:clear  //or sail artisan otimize:clear
```
---
To install mailgun, if needed:
```
composer require symfony/mailgun-mailer symfony/http-client
```
and check configuration <project_root>/config/services.php:
```
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.eu.mailgun.net'),
    ],
```
Also, check in .env file is MAIL_FROM_ADDRESS set:

```
MAIL_FROM_ADDRESS=example@gmail.com
```
---
If changes are made on any of configuration files, be sure to run:
```
./vendor/bin/sail artisan otimize:clear
```
