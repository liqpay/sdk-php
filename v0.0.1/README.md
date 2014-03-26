sdk-php
=======

LiqPay SDK for php


Использование
-----

#### создание кнопки для оплаты ####

```php
$configArr = array(

// Публичный ключ - идентификатор магазина. Получить здесь https://www.liqpay.com/admin/business
    'public_key' => '',

// Приватный ключ. Получить здесь https://www.liqpay.com/admin/business
    'private_key' => '',

// URL на который будет произведена переадресация после завершения оплаты
    'result_url' => '',

// URL API для уведомлений о статусе оплаты (сервер->сервер)
    'server_url' => '',

// Тип оплаты: buy - покупка в магазине, donate - пожертвование.
    'type' => 'buy',

// URL для передачи данных
    'action' => 'https://www.liqpay.com/api/pay',

// Валюта платежа. Доступно: USD, UAH, RUB, EUR
    'currency' => 'RUB',

// Язык платежной страницы (например ru или en)
    'language' => 'ru',

// Шаблон для формирования сигнатуры платежа
    'paySignTpl' => '{private_key}{amount}{currency}{public_key}{order_id}{type}{description}{result_url}{server_url}',

// Шаблон для формирования сигнатуры уведомления
    'notifSignTpl' => '{private_key}{amount}{currency}{public_key}{order_id}{type}{description}{status}{transaction_id}{sender_phone}',

// Показывать кнопку отправки формы для передачи данных
    'payButton' => true,

// Автоматически отправлять форму для передачи данных
    'autoSend' => false,
);

$amount = 2.56;
$currency = 'RUB';
$description = 'Demo order';

$liqpay = new Liqpay($configArr);
echo $liqpay->getPayForm($order_id, $amount, $description);
```

### возможные параметры ###

**параметр**                    | **обязательный**
--------------------------------|--------------------------------
`public_key`                    | `Да`
`amount`                        | `Да`
`currency`                      | `Да`
`description`                   | `Да`
`order_id`                      | `Нет`
`result_url`                    | `Нет`
`server_url`                    | `Нет`
`type`                          | `Нет`
`language`                      | `Нет`

