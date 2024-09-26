# Package for Painless Payment Integration

---

## Table of Contents

- [Getting Started](#getting-started)
- [Installation](#installation)
- [Basic Usage](#basic-usage)
    - [Using Helpers](#using-helpers)
    - [Using Laravel Facades](#using-laravel-facades)
- [Migration](#migration)
- [Model](#model)
- [TODO: Suggestions to Extend](#suggestions-to-extend)

---

## Getting Started

This package is designed for fast and straightforward payment integration, providing basic components such as migrations, models, enums, and providers out of the box. Currently, it supports only the `Imoje (ING)` payment gateway with a pre-configured webhook.

### Installation

First, add a new `vcs` repository to your `composer.json`. Request an access token for this repository from `@record223` or `@Qionar`.

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/ONXIT/pay-gateway.git"
    }
  ]
}
```

Next, install the package by running:

```bash
composer require ONXIT/pay-gateway
```

### Basic Usage

After installation, run the following command to publish all configurations, migrations, and providers:

```bash
php artisan pay-gateway:install
```

Set the required fields in your `.env` file, as specified in `config/pay-gateway.php`. To use the provider in your service, you can either use helpers or facades.

#### Using Helpers

To use the default payment driver defined in `.env`:

```php
paymentProcessor();
```

Or specify a driver explicitly:

```php
paymentProcessor('imoje');
```

Alternatively, pass a class implementing the `PaymentProvider` contract:

```php
paymentProcessor(new \Qionar\PayGateway\Strategies\ImojeProvider());
```

#### Using Laravel Facades

If you prefer using facades and the `paamayim nekudotayim (::)` operator, you can access the processor like this:

```php
// As a factory method
\Qionar\PayGateway\Facades\PaymentProcessor::provider();

// With a specific driver
\Qionar\PayGateway\Facades\PaymentProcessor::provider('imoje');

// With an instance
\Qionar\PayGateway\Facades\PaymentProcessor::withProvider(new \Qionar\PayGateway\Strategies\ImojeProvider());
```

### Migration

This migration provides the default structure used in most ONX projects. Feel free to customize or extend it by adding fields, but make sure the following fields remain unchanged if you plan to use the out-of-the-box `WebhookController` handler:
- `uuid`
- `status`
- `provider_data`

```php
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->uuid('uuid');
    $table->string('provider_id')->nullable();
    $table->enum('provider', \Qionar\PayGateway\Enums\PaymentProvider::values());
    $table->enum('status', \Qionar\PayGateway\Enums\TransactionStatus::values());
    $table->bigInteger('amount')->unsigned();
    $table->json('provider_data')->nullable();
    $table->timestamps();
});
```

### Model

The package also includes a `Transaction` model that you can use, extend, or replace with your own model. Note that the package overwrites the `create` method with `forceCreate` to ensure that custom fields don't need to be explicitly declared in `$fillable`.

---

### Suggestions to Extend:

1. **Webhook Configuration**: Add instructions on configuring and testing webhooks.
2. **Error Handling**: Consider providing best practices for handling transaction failures or webhook verification issues.
3. **Testing**: Include a section on how to test the integration (unit tests or testing environments).
4. **FAQ/Troubleshooting**: Add a brief section with common issues and solutions.
