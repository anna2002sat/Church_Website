<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'name'=> 'ℤ𝔸 ℤ𝕀ℝ𝕂𝕆𝕐',

//    'bootstrap' => ['debug'],
    'modules' => [
//        'debug' => [
//            'class' => 'yii\debug\Module',
//            'allowedIPs' => ['*'],
//            ],
//        'gii' => [
//            'class' => 'yii\gii\Module',
//            ],
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
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
    'params' => $params,
];
