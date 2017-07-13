Yii2 SendMachine API Client
===========================
Yii2 implementation of the Sendmachine API

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer require --prefer-dist mrbig00/yii2-sendmachine "*"
```

or add

```
"mrbig00/yii2-sendmachine": "*"
```

to the require section of your `composer.json` file.


Configuration
-----

Once the extension is installed, set on your configuration file, in components section

```php
'components' => [ 
    
    'sendmachine' => [
        'sendmachine' => [
            'class'    => 'mrbig00\sendmachine\Sendmachine',
            'username' => '__YOUR_USERNAME_FROM_SMTP_PANEL__',
            'password' => '__YOUR_PASSWORD_FROM_SMTP_PANEL__'
        ],
    ]
    
]
```

Usage
----
```php
\Yii::$app->sendmachine->client
```
You can find more docs on: http://developers.sendmachine.com/

Based on
---
https://github.com/Sendmachine