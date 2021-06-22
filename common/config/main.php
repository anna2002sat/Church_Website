<?php
define('YII_DEBUG', true);
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'mailer' => [
                    'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => 'ssl',
                'host' => 'smtp.gmail.com',
                'port' => '465',
                'username' => 'anna2002sat@gmail.com',
                'password' => 'anna17001',
                'streamOptions'=>[
                    'ssl'=>[
                        'allow_self_signed'=>true,
                        'verify_peer'=>false,
                        'verify_peer_name'=>false
                    ],
                ],
            ],
        ],
    ],

];
