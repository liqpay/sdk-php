sdk-php
=======

LiqPay SDK-PHP

Documentation https://www.liqpay.ua/documentation/en


Встановлення
Для втсновлення плагіна за допомогою composer необхідно додати в composer.json секцію repositories:
```
"repositories": [
    {
        "type":"package",
        "package": {
          "name": "liqpay/sdk-php",
          "version":"master",
          "source": {
              "url": "https://github.com/liqpay/sdk-php.git",
              "type": "git",
              "reference":"master"
            }
        }
    }
],

І в секцію require:
 "require": {
    "liqpay/sdk-php": "dev-master"
}
