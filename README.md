sdk-php
=======

LiqPay SDK for php


Использование
-----

#### создание кнопки для оплаты ####

```php
$liqpay = new LiqPay($public_key, $private_key);

echo $liqpay->getForm(array('order_id' => '123456', 'amount' => 5, 'currency' => 'USD'));
```

### возможные параметры ###

**параметр**                    | **обязательный**
--------------------------------|--------------------------------
`amount`                        | `Да`
`currency`                      | `Да`
`description`                   | `Да`
`order_id`                      | `Нет`
`result_url`                    | `Нет`
`server_url`                    | `Нет`
`type`                          | `Нет`
`language`                      | `Нет`


#### проверка статуса платежа ####

```php
$liqpay = new LiqPay($public_key, $private_key);

$res = $liqpay->api("payment/status", array('order_id' => 256));

/*
Результатом print_r($res) будет

stdClass Object (
    [result] => success
    [payment_id] => 10240512
    [order_id] => 256
    [amount] => 9.99
    [currency] => UAH
    [description] => some desc
    [status] => success
)
*/
```
