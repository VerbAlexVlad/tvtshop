<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name'=>'точьВточь',
    'language' => 'ru_RU', // for example, Russian
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'categories/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'yii2images' => [
            'class' => 'app\base\Yii2imageModule',
            //be sure, that permissions ok
            //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
            'imagesStorePath' => 'images/store', //path to origin images
            'imagesCachePath' => 'images/cache', //path to resized copies
            'graphicsLibrary' => 'Imagick', //but really its better to use 'Imagick'
            'placeHolderPath' => '@webroot/images/placeHolder.png', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'admin',
            'defaultRoute' => 'default/index',
        ],
        'admins' => [
            'class' => 'app\modules\admins\Module',
          'layout' => 'admin',
            'defaultRoute' => 'default/index',
        ],
    ],
    'components' => [
        'session' => [
            'class' => 'yii\web\DbSession',
//             'db' => 'tvtshop',  // ID компонента для взаимодействия с БД. По умолчанию 'db'.
            'sessionTable' => 'session', // название таблицы для хранения данных сессии. По умолчанию 'session'.
        ],
        'db2' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=mybd',
            'username' => 'root',
            'password' => 'pKUo7P0LeJ'
        ],
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
            'bundles' => [
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],
//         'view' => [
//             'class' => '\rmrevin\yii\minify\View',
// //             'enableMinify' => YII_DEBUG,
//             'concatCss' => true, // concatenate css
//             'minifyCss' => true, // minificate css
//             'concatJs' => true, // concatenate js
//             'minifyJs' => true, // minificate js
//             'minifyOutput' => true, // minificate result html page
//             'webPath' => '@web', // path alias to web base
//             'basePath' => '@webroot', // path alias to web base
//             'minifyPath' => '@webroot/upload/minify', // path alias to save minify result
//             'jsPosition' => [ \yii\web\View::POS_END ], // positions of js files to be minified
//             'forceCharset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
//             'expandImports' => true, // whether to change @import on content
//             'compressOptions' => ['extra' => true], // options for compress
// //             'excludeFiles' => [
// //                 'jquery.js', // exclude this file from minification
// //                 'app-[^.].js', // you may use regexp
// //             ],
//         ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=9306;',
            'username' => '',
            'password' => '',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '8MoFCXRzMW9sHJ1HrE3_naNFpQ_rJDCe',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache', // Используем хранилище yii\caching\FileCache
        ],
//       'cache' => [
//             'class' => 'yii\redis\Cache',
//             'redis' => [
//                 'hostname' => '127.0.0.1',
//                 'port' => 6379,
//                 'database' => 0,
//             ]
//         ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'htmlLayout' => 'layouts/html',
            'textLayout' => 'layouts/text',
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['info@tvtshop.ru' => 'точьВточь'],
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'info@tvtshop.ru',
                'password' => 'PafnimayShryeH8',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
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
        'as access' => [
            'class' => 'mdm\admin\components\AccessControl',
            'allowActions' => [
                '*',
//                'upload/*',
                // The actions listed here will be allowed to everyone including guests.
                // So, 'admin/*' should not appear here in the production, of course.
                // But in the earlier stages of your development, you may probably want to
                // add a lot of actions here until you finally completed setting up rbac,
                // otherwise you may not even take a first step.
            ]

        ],
        'inflection' => [
            'class' => 'wapmorgan\yii2inflection\Inflection'
        ],
        'urlManager' => require __DIR__ . '/urlManager.php',

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
