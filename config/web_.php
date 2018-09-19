<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout' => 'getbike',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@uploadBikePhoto'   => '@app/web/upload/bikes',
        '@uploadBikePhotoWeb'   => '/upload/bikes',
    ],
    'components' => [
        'request' => [
            'baseUrl'=> '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'tuAuLhanidaJh8XAw_0AH-inDPcnH89Q',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                '/about' => 'site/about',
                '/second' => 'site/second',
                '/third' => 'site/third',
                '/final' => 'site/final',
                '/mailtest' => 'site/mailtest',
                '/rental/auth/<hash:\w+>' => 'rental/rental/auth',
                '/rental/<action>' => 'rental/rental/<action>'
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['getbike@leads24.info' => 'getbike.io'],],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                /*                'host' => 'ssl://smtp.yandex.com',
                                'username' => 'info@yar-bor.ru',
                                'password' => 'Xhomq7yr',
                                'port' => '465',*/
                'host' => 'leads24.info',
                'username' => 'getbike@leads24.info',
                'password' => 'X9LG8TfqQc',
                //'port' => '587',
                //'encryption' => 'tls',
                //'encryption' => 'SSL',

            ],
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
        'db' => $db,
        'paypal'=> [
            'class'        => 'davidjeddy\Paypal',
            'clientId'     => 'you_client_id',
            'clientSecret' => 'you_client_secret',
            'isProduction' => false,
            // These properties would be found in the Paypal sdk_config.ini
            'config'       => [
                'http.ConnectionTimeOut' => 30,
                'http.Retry'             => 1,
                'mode'                   => \davidjeddy\Paypal::MODE_SANDBOX, // development (sandbox) or production (live) mode
                'log.LogEnabled'         => YII_DEBUG ? 1 : 0,
                'log.FileName'           => '@runtime/logs/paypal.log',
                'log.LogLevel'           => \davidjeddy\Paypal::LOG_LEVEL_FINE,
            ]
        ],
    ],
    'modules' => [
        'rental' => [
            'class' => 'app\modules\rental\Module',
            'layout' => 'main'
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'main'
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1','109.195.115.122', '91.228.64.18', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
