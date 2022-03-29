<?php

return [
    'enablePrettyUrl' => true,
    'showScriptName'  => false,
    'rules' => [
        '<action:(contact|login|reg|sellerreg|profile|resetpassword|sendemail|verifyemail|privacy-policy|userparam)>' => 'site/<action>',
        '<action:(init)>' => 'rbac/<action>',
        'customer-info/<alias:\w+>' => 'footer-info/customer-info',
        [
            'class' => 'yii\web\GroupUrlRule',
            'routePrefix' => 'categories',
            'rules' => [

                'shop_<shop_id:\d+>/category-<category_alias:\w+>-for-<all:\w+>' => 'view',
                'shop_<shop_id:\d+>/category-<category_alias:\w+>' => 'view',
                'category-<category_alias:\w+>-for-<all:\w+>' => 'view',
                'category-<category_alias:\w+>' => 'view',
                'shop_<shop_id:\d+>/for-<all:\w+>' => 'view',
                'for-<all:\w+>' => 'view',
                'shop_<shop_id:\d+>' => 'index',
                'shop_<shop_id:\d+>/search' => 'search',
                'search' => 'search',
            ],
        ],
        [
            'class' => 'yii\web\GroupUrlRule',
            'routePrefix' => 'admin/products',
            'rules' => [
                'admin/products/category-<category_alias:\w+>-for-<all:\w+>' => 'index',
                'admin/products/category-<category_alias:\w+>' => 'index',
            ],
        ],
        [
            'class' => 'yii\web\GroupUrlRule',
            'routePrefix' => 'brand',
            'rules' => [
                'shop_<shop_id:\d+>//brand-<brand_alias:\w+>/category-<category_alias:\w+>-for-<all:\w+>' => 'view',
                'shop_<shop_id:\d+>//brand-<brand_alias:\w+>/category-<category_alias:\w+>' => 'view',
                'brand-<brand_alias:\w+>/category-<category_alias:\w+>-for-<all:\w+>' => 'view',
                'brand-<brand_alias:\w+>/category-<category_alias:\w+>' => 'view',
                'shop_<shop_id:\d+>/brand-<brand_alias:\w+>/for-<all:\w+>' => 'view',
                'brand-<brand_alias:\w+>/for-<all:\w+>' => 'view',
                'shop_<shop_id:\d+>/brand-<brand_alias:\w+>' => 'index',
                'brand-<brand_alias:\w+>' => 'view',
            ],
        ],
        [
            'class' => 'yii\web\GroupUrlRule',
            'prefix' => 'user',
            'rules' => [
                'param' => 'userparam/param',
                'order' => 'userparam/order',
            ],
        ],
        [
            'class' => 'yii\web\GroupUrlRule',
            'routePrefix' => 'products',
            'rules' => [
                'shop_<shop_id:\d+>/product-<product_alias:\w+>' => 'view',
                'product-<product_alias:\w+>' => 'view',
            ],
        ],
      
        'link-<link_id:\d+>' => 'link/index',
      
        'favorites'       => 'favorites/view',
        'favorites-shops' => 'favorites-shops/view',

        'userparam/update/<id:\d+>' => 'userparam/update',

        'cart/<shop_id:\d+>' => 'cart/view',
        'sitemap.xml'     => 'site/sitemap', //карта сайта
        'yml-sitemap.xml' => 'site/yml-sitemap',
        'turbo-yml-sitemap.xml' => 'site/turbo-yml-sitemap',
        'yandex-yml-sitemap.xml' => 'site/yandex-yml-sitemap',
        'satom-yml-sitemap.xml' => 'site/satom-yml-sitemap',
        'robots.txt'      => 'web/robots.txt',
    ]
];

