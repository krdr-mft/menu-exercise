# Instalation instructions

It is assumed that you have globaly installed PHP, Composer and development server (Sail, eg). If Sail is used, it should be globaly accessible. Asure that mailhog is available for checking mail sending functionality

## Database

1. To create database structer run `php artisan migrate` or `sail artisan migrate`
2 Seed database with `php artisan db:seed` or `sail artisan db:seed`
3. If needed, database state can be refreshed with `php artisan migrate:refresh --seed` or `sail artisan migrate:refresh --seed`

## Application

To avoid problems with storing session data, wirting rights should be set on <project_root>/storage folder:

```
chmod -R gu+w storage
chmod -R guo+w storage
php artisan optimize:clear  //or sail artisan otimize:clear
```

Install mailgun
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

If changes are made on any of configuration files, run:
```
php artisan optimize:clear  //or sail artisan otimize:clear
```

## Running
```
php artisan serve
```
or:
```
sail up
```

Application will run on URL stated in .env, parameter APP_URL and on port APP_PORT. Eg:
```
APP_URL=http://localhost
APP_PORT=8081
```
means that application is accessible on http://localhost:8081

## REST API

GET /api/currencies list of avaliable currencies
GET /api/currencies/rates/refreshby/{ISOcode} refreshes exchange rates with data from external service. {ISOcode} is 3 character ISO code of base currency (USD)
GET /api/currencies/rates/{ISOcode} retrieves stored exchange rates for base currency (USD)
GET /api/orders list of all orders made
POST /api/order creates new order. Expects DTO of following structure {"buy":<iso code>,"amount": <amount>, "for": <iso code>}. In Postman, it Body should be sent as "raw" and of "JSON" type. Example: {"buy":"GBP","amount":100, "for": "USD"}

GET /api/order/actions  list of order actions, actions executed when order is created (allow discount for EUR, send mail for GBP)
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
GET /api/order/action/types list of action types (discount, sendmail)
PATCH /api/order/action/{action}/for/{currency} Updates action, where {action} is name of action (discount or sendmail) and {currency} denotes on whic currency action relates. Example:

`PATCH /api/order/action/discount/for/EUR` and with body `{parameter: 5}` will set 5% discount on purchasing EUR.

## Troubleshooting
If sail wont start beacuse of port collison, change ports stated in APP_PORT and DB_PORT parameters, then refresh configuration.
