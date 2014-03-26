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
