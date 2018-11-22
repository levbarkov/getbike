<?php

$params = require __DIR__ . '/params.php';
$i18n_config = require __DIR__ . '/i18n.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout' => 'getbike',
    'defaultRoute' => 'dev/index',
    'language' => 'en',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@uploadBikePhoto' => '@app/web/upload/bikes',
        '@uploadBikePhotoWeb' => '/upload/bikes',
    ],
    'components' => [
        'request' => [
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'tuAuLhanidaJh8XAw_0AH-inDPcnH89Q',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable'=>'{{%source_message}}',
                    'messageTable'=>'{{%message}}',
                    'enableCaching' => false,
                    'cachingDuration' => 10,
                    'forceTranslation'=>true,
                    //'basePath' => '@app/messages',
                    'sourceLanguage' => 'en',
                 /*   'fileMap' => [
                        'app' => 'app/app.php',
                        'admin'       => 'admin/admin.php',
                        'admin/operations' => 'admin/operations.php',
                    ],*/
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'dev/error',
        ],
        'urlManager' => [
            'class' => 'app\components\CustomUrlManager',
            'languages' => ['en','ru'],
            'enablePrettyUrl' => true,
            'enableDefaultLanguageUrlCode' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'ignoreLanguageUrlPatterns' => ['#^admin/#' => '#^admin/#'],
            'rules' => [
                '/' => 'dev/index',
/*              '/index' => 'site/index',
                '/about' => 'site/about',
                '/insurance' => 'site/insurance',
                '/second' => 'site/second',
                '/third' => 'site/third',
                '/final' => 'site/final',
                '/mailtest' => 'site/mailtest',
                '/hiw' => 'dev/hiw',
                '/delivery' => 'dev/delivery',
                '/contacts' => 'dev/contacts',*/
                'sitemap.xml' => 'dev/sitemap',
                '/admin' => 'admin/zakaz/index',
                '/admin/<action>' => 'admin/<action>',
                '/admin/<controller>/<action>' => 'admin/<controller>/<action>',
                '/rental/auth/<hash:\w+>' => 'rental/rental/auth',
                '/rental/<action>' => 'rental/rental/<action>',
//                '/dev/<action>' => 'dev/<action>',
                '/site/<action>' => 'site/<action>',
                '/info/<iso:[\w\-]+>/<region:[\w\-]+>/<title:[\w\-]+>' => 'dev/article',
                '/<country:[\w\-]+>/<region:[\w\-]+>/<step:[\w\-]+>' => 'dev/booking',
                //'/<country:[\w\-]+>/<region:[\w\-]+>/' => 'dev/booking',
                '/<country:[\w\-]+>/<region:[\w\-]+>' => 'dev/booking',
                '/index' => 'dev/index',
                '/second' => 'dev/second',
                '/third' => 'dev/third',
                '/final' => 'dev/final',
                '/page/<page:[\w\-]+>' => 'dev/pages',
                '/<action>' => 'dev/<action>'
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['info@getbike.io' => 'getbike.io'],],
            'transport' => [
                'class' => 'Swift_MailTransport',
                //'host' => 'leads24.info',
                //'username' => 'getbike@leads24.info',
                //'password' => 'X9LG8TfqQc',
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
    'params' => $params+$i18n_config,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '109.195.115.122', '91.228.64.18', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '91.228.64.18', '::1'],
    ];
}

return $config;
