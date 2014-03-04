sdk-php
=======

LiqPay SDK for php


Использование
-----

#### создание кнопки для оплаты ####

```php
liqpay:init(PublicKey, PrivateKey)-> liqpay:liqpay()
liqpay:cnb_form(Liqpay, Params)-> iolist()
```

    1> Lp = liqpay:init("i42344324", "fsdfsdfsdf").
    {liqpay,"i42344324","fsdfsdfsdf"}
    2> liqpay:cnb_form(Lp, [{amount, 1}, {currency, "USD"}, {description, "test"}]).
    "<form method=\"POST\" action=\"https://www.liqpay.com/api/pay\">\n\t
    <input type=\"hidden\" name=\"amount\" value=\"1\" />\n\t
    <input type=\"hidden\" name=\"currency\" value=\"USD\" />\n\t
    <input type=\"hidden\" name=\"description\" value=\"test\" />\n\t
    <input type=\"hidden\" name=\"signature\" value=\"kTjHpD9zLNVU0NO5dDBcCmwOOmA=\" />\n\t
    <input type=\"image\" src=\"//static.liqpay.com/buttons/p1ru.png\" name=\"btn_text\"
     class=\"liqpay_pay_button\" />\n</form>\n"

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
`order_id`                      | `Нет`
`order_id`                      | `Нет`



#### создание сигнатуры для оплаты ####
    1> liqpay:cnb_signature(Lp, [{amount, 1}, {currency, "USD"}, {description, "test"}]).
    "kTjHpD9zLNVU0NO5dDBcCmwOOmA="