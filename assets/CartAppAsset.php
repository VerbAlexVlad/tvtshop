<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CartAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/easy-autocomplete.min.css',
        'css/styles.css',
        'css/cart-styles.css',
        'css/jquery.mmenu.all.css',
    ];
    public $js = [
//        'js/jquery-ui.min.js',
        'js/jquery.easy-autocomplete.min.js',
        'js/jquery.mmenu.all.js',
        'js/main.js',
//        'js/fancybox.Event.js',
        'js/cart-main.js',
    ];
    public $depends = [
        'rmrevin\yii\fontawesome\NpmFreeAssetBundle',
    ];
}
