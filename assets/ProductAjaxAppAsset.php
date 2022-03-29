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
class ProductAjaxAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/magiczoomplus.css',
        'css/product-styles.css',
    ];
    public $js = [
        'js/main.js',
        'js/magiczoomplus.js',
        'js/product-main.js',
    ];
    public $depends = [
        'rmrevin\yii\fontawesome\NpmFreeAssetBundle',
    ];
}
