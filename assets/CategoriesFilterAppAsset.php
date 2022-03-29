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
class CategoriesFilterAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/categories-filter-styles.css',
    ];
    public $js = [
        'js/categories-filter.js',
    ];
//    public $depends = [
//        'rmrevin\yii\fontawesome\NpmFreeAssetBundle',
////        'yii\web\YiiAsset',
////        'yii\bootstrap\BootstrapAsset',
//    ];
}
