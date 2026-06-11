# Privat24 Business API implementation for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sashalenz/privat24-business-api.svg?style=flat-square)](https://packagist.org/packages/sashalenz/privat24-business-api)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sashalenz/privat24-business-api/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sashalenz/privat24-business-api/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sashalenz/privat24-business-api/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sashalenz/privat24-business-api/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sashalenz/privat24-business-api.svg?style=flat-square)](https://packagist.org/packages/sashalenz/privat24-business-api)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require sashalenz/privat24-business-api
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="privat24-business-api-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

### Виписки (statements)

```php
use Sashalenz\Privat24BusinessApi\Privat24BusinessApi;

// Транзакції за період (з курсорною пагінацією)
$response = Privat24BusinessApi::statements()
    ->token($token)
    ->transactions($startDate, $endDate, $acc, $followId, 20);

foreach ($response->transactions as $tx) {
    // Sashalenz\Privat24BusinessApi\Types\Transaction
}

// Поточний операційний день / фінальна виписка за попередній день
Privat24BusinessApi::statements()->token($token)->interimTransactions();
Privat24BusinessApi::statements()->token($token)->finalTransactions();

// Баланси
Privat24BusinessApi::statements()->token($token)->balance($startDate);
Privat24BusinessApi::statements()->token($token)->interimBalance();
Privat24BusinessApi::statements()->token($token)->finalBalance();

// Health-check: phase === 'WRK' — банк приймає запити
Privat24BusinessApi::statements()->token($token)->settings();
```

### Платежі (proxy/payment)

Створений через API платіж **не рухає кошти**: він зʼявляється у «Приват24
для бізнесу» зі статусом `new` і чекає підпису КЕП у кабінеті. До підписання
його можна видалити через `delete()`.

```php
use Sashalenz\Privat24BusinessApi\Privat24BusinessApi;
use Sashalenz\Privat24BusinessApi\RequestData\Payments\CreatePaymentRequest;

// Створення платіжного доручення
$response = Privat24BusinessApi::payments()
    ->token($token)
    ->create(new CreatePaymentRequest(
        document_number: '42',
        payer_account: 'UA77305299...',          // IBAN відправника
        payment_naming: 'ТОВ "Отримувач"',
        payment_amount: '1250.50',               // decimal-рядок
        payment_destination: 'Оплата за послуги згідно рахунку №42',
        recipient_account: 'UA74305299...',      // IBAN отримувача
        recipient_nceo: '12345678',              // ЄДРПОУ/ІПН ('0000000000' — фізособа)
    ));

$response->payment_ref;       // референс — він же REF у виписці після проведення
$response->payment_pack_ref;

// Стан платежу
$state = Privat24BusinessApi::payments()->token($token)->get($response->payment_ref);
$state->payment_status;       // 'new' — очікує підписання

// Видалення непідписаного платежу (POST, 204)
Privat24BusinessApi::payments()->token($token)->delete($response->payment_ref);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [sashalenz](https://github.com/sashalenz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
