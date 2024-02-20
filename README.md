# Tabadul Laravel Package

The Tabadul Laravel package is a powerful integration tool for seamlessly connecting your Laravel application with the Tabadul payment gateway. It simplifies the process of initiating payments and checking payment statuses, providing a straightforward and efficient solution for e-commerce transactions.

## Key Features

- **Easy Integration:** Effortlessly integrate your Laravel application with the Tabadul payment gateway.

- **Secure Payments:** Ensure secure and reliable payment processing with Tabadul's robust infrastructure.

- **Configuration Flexibility:** Easily manage and customize your Tabadul API credentials and parameters through Laravel's configuration files.

- **Payment Status Checks:** Check the status of payments with ease, allowing you to keep track of successful transactions or detect any issues promptly.

## Installation

Install the package using Composer:

```bash
composer require vendor/tabul-laravel
```

Publish the configuration file to customize your Tabadul API credentials:

```bash
php artisan vendor:publish --tag=config
```

## Getting Started

Initialize payments and check their status effortlessly with the Tabadul Laravel package:

```php
use Vendor\TabadulPaymentController;

// Instantiate the controller
$tabadulController = app(TabadulPaymentController::class);

// Process a payment
$tabadulController->processPayment($request);

// Check payment status
$tabadulController->checkPaymentStatus($orderId);
```

## Requirements

- PHP 7.4 or later
- Laravel 8 or later

## Documentation

For detailed documentation and examples, visit the [official documentation page](https://jodx.dev).
