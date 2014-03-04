<?php
/**
 * Liqpay Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category        Liqpay
 * @package         Liqpay
 * @version         0.0.1
 * @author          Liqpay
 * @copyright       Copyright (c) 2014 Liqpay
 * @license         http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *
 * EXTENSION INFORMATION
 *
 * LIQPAY API       https://www.liqpay.com/ru/doc
 *
 */

return array(

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