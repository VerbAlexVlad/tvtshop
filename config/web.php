<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'точьВточь',
    'language' => 'ru_RU', // for example, Russian
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'categories/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
    ],
    'modules' => [
        'comments' => [
            'class' => 'mickey\commentator\Module',
            'userModelClass' => 'app\models\User',
            'useSettingsFromDB' => true,
            'isSuperuser' => Yii::$app->user->identity->isAdmin,
            'dateFormat' => 'd.m.Y',
            'notifyAdmin' => true,
            'premoderate' => true,
            'fromEmail' => 'info@tvtshop.ru',
            'adminEmail' => 'info@tvtshop.ru'
//            'userEmailField' => 'email',
//            'usernameField' => 'username',
        ],
        'yii2images' => [
            'class' => 'app\base\Yii2imageModule',
            //be sure, that permissions ok
            //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
            'imagesStorePath' => 'images/store', //path to origin images
            'imagesCachePath' => 'images/cache', //path to resized copies
            'graphicsLibrary' => 'Imagick', //but really its better to use 'Imagick'
            'placeHolderPath' => '@webroot/images/placeHolder.png', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
        ],
        'gallery' => [
            'class' => 'dvizh\gallery\Module',
            'imagesStorePath' => dirname(dirname(__DIR__)) . '/frontend/web/images/store', //path to origin images
            'imagesCachePath' => dirname(dirname(__DIR__)) . '/frontend/web/images/cache', //path to resized copies
            'graphicsLibrary' => 'GD',
            'placeHolderPath' => '@webroot/images/placeHolder.png',
            'adminRoles' => ['administrator', 'admin', 'superadmin'],
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'admins' => ['Alexa'],
        ],
        'rbac' => [
            'class'         => 'mdm\admin\Module',
            'layout'        => 'top-menu',
            'mainLayout'    => '@app/modules/admin/views/layouts/admin.php',
            'controllerMap' => [
                'assignment' => [
                    'class'         => 'mdm\admin\controllers\AssignmentController',
                    /* 'userClassName' => 'app\models\User', */
                    'idField'       => 'id',
                    'usernameField' => 'username',
                ],
            ],

        ],
//        'rbac' =>  [
//            'class' => 'johnitvn\rbacplus\Module',
//            'userModelClassName'=>'app\models\User',
//            'userModelIdField'=>'id',
//            'userModelLoginField'=>'username',
//            'userModelLoginFieldLabel'=>null,
//            'userModelExtraDataColumls'=>null,
//            'beforeCreateController'=>null,
//            'beforeAction'=>null
//        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'admin',
            'defaultRoute' => 'default/index',
        ],

    ],

    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
//            'defaultRoles' => ['user','moder','admin', 'seller'], //здесь прописываем роли
//            //зададим куда будут сохраняться наши файлы конфигураций RBAC
//            'itemFile' => '@common/components/rbac/items.php',
//            'assignmentFile' => '@common/components/rbac/assignments.php',
//            'ruleFile' => '@common/components/rbac/rules.php'
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'thousandSeparator' => '&nbsp;',
            'decimalSeparator' => '.'
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
            'class' => 'yii\caching\FileCache',
        ],
//         'user' => [
//             'identityClass' => 'app\models\User',
//             'enableAutoLogin' => true,
//         ],
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
        'db' => $db,
        'inflection' => [
            'class' => 'wapmorgan\yii2inflection\Inflection'
        ],
        'urlManager' => require __DIR__ . '/urlManager.php',

    ],

    'as access'    => [
        'class'        => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'user/*',
            'delivery/*',
            'payment/*',
            'footer-info/*',
            'brands/*',
            'additional-info/*',
            'userparams/*',
            'categories/*',
            'orders/*',
            'products/*',
            'favorites-products/*',
            'yii2images/*',
            'cart/*',
            'debug/*',
        ]
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
