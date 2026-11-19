# LiqPay Payment Module

> **LiqPay PHP SDK** for seamless integration with [LiqPay](https://www.liqpay.ua) payment gateway.

---

## Documentation

For full API reference and details, see the LiqPay API documentation:  
[LiqPay API Docs](https://www.liqpay.ua/documentation/en)


## Table of Contents

1. [Installation](#installation)
2. [Quick Start](#quick-start)
3. [Usage](#usage)
4. [Public Methods](#public-methods)
    - [Methods Summary](#methods-summary)
    - [`api`](#api)
    - [`get_response_code`](#get_response_code)
    - [`cnb_form`](#cnb_form)
    - [`cnb_form_raw`](#cnb_form_raw)
    - [`cnb_signature`](#cnb_signature)
    - [`decode_params`](#decode_params)
    - [`str_to_sign`](#str_to_sign)

---

## Installation

Install via Composer:

```bash
composer require liqpay/liqpay
```

Or include manually:

```php
require_once 'path/to/LiqPay.php';
```

---

## Quick Start

```php
use LiqPay;

$liqpay = new LiqPay('your_public_key', 'your_private_key');

// Check payment status
$response = $liqpay->api('payment/status', [
    'version'  => 3,
    'action'   => 'status',
    'order_id' => 'order123',
]);

// Generate checkout form
echo $liqpay->cnb_form([
    'version'     => 3,
    'action'      => 'pay',
    'amount'      => 100.50,
    'currency'    => 'USD',
    'description' => 'Order #123 Payment',
    'language'    => 'en',
]);
```

---

## Usage

---

## Public Methods

### Methods Summary

| Method               | Signature                                                                | Description                                  |
|----------------------|--------------------------------------------------------------------------|----------------------------------------------|
| `api`                | `api(string $path, array $params = [], int $timeout = 5): object\|array` | Call LiqPay API and return parsed response. |
| `get_response_code`  | `get_response_code(): int\|null`                                         | Last HTTP status code from API.             |
| `cnb_form`           | `cnb_form(array $params): string`                                        | Render HTML checkout form.                  |
| `cnb_form_raw`       | `cnb_form_raw(array $params): array`                                     | Raw URL, data, and signature.               |
| `cnb_signature`      | `cnb_signature(array $params): string`                                   | Compute data signature for checkout.        |
| `decode_params`      | `decode_params(string $data): array`                                     | Decode Base64‑encoded payload.              |
| `str_to_sign`        | `str_to_sign(string $str, string $algo = 'sha1'): string`                | Generate signature with specific algorithm. |

---

### `__construct`

Initialize the LiqPay client with your credentials.

```php
public function __construct(
    string $public_key,
    string $private_key,
    string|null $api_url = null
)
```

- **Parameters:**
    - `$public_key` _(string)_ — Your LiqPay public key.
    - `$private_key` _(string)_ — Your LiqPay private key.
    - `$api_url` _(string|null)_ — Override default API endpoint.
- **Exceptions:**
    - `InvalidArgumentException` if keys are empty.

---

### `api`

Send a request to a LiqPay API endpoint and get a parsed response.

```php
public function api(
    string $path,
    array $params = [],
    int $timeout = 5
): object\|array
```

- **Parameters:**
    - `$path` _(string)_ — Endpoint path (e.g., `'payment/status'`).
    - `$params` _(array)_ — Must include `version` and `action` (e.g., `'pay'`).
    - `$timeout` _(int)_ — Timeout in seconds (connect + exec).
- **Returns:**
    - JSON-decoded object on success.
    - `['error' => '...']` on failure.
- **Exceptions:**
    - `InvalidArgumentException` if required params missing.

---

### `get_response_code`

Retrieve HTTP status code from the last `api()` call.

```php
public function get_response_code(): int\|null
```

- **Returns:**
    - HTTP status code (e.g., `200`) or `null`.

---

### `cnb_form`

Render a fully functional HTML checkout form with LiqPay JavaScript SDK.

```php
public function cnb_form(array $params): string
```

- **Parameters:**
    - `version`, `action`, `amount`, `currency`, `description` _(required)_
    - `language` _(optional)_ — `'uk'`, `'ru'`, or `'en'`.
- **Returns:**
    - HTML `<form>` string with embedded button.
- **Example:**

```php
echo $liqpay->cnb_form([
  'version'     => 3,
  'action'      => 'paydonate',
  'amount'      => 5,
  'currency'    => 'UAH',
  'description' => 'Support project',
  'language'    => 'uk',
]);
```

---

### `cnb_form_raw`

Get raw payload data for custom form implementations.

```php
public function cnb_form_raw(array $params): array
```

- **Returns:**
  ```php
  [
    'url'       => 'https://www.liqpay.ua/api/3/checkout',
    'data'      => '<Base64 JSON>',
    'signature' => '<Signature>',
  ]
  ```

---

### `cnb_signature`

Compute the signature for given parameters (used in custom integrations).

```php
public function cnb_signature(array $params): string
```

---

### `decode_params`

Decode Base64‑encoded payment data back to an array.

```php
public function decode_params(string $data): array
```

---

### `str_to_sign`

Generate a Base64‑SHA1 signature by default for any string.

```php
public function str_to_sign(string $str, string $algo = 'sha1'): string
```

---