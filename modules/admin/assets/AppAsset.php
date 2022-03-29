<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/web';
    public $css = [
//        'vendor/bootstrap/css/bootstrap.min.css',
        'vendor/metisMenu/metisMenu.min.css',
        'vendor/datatables-plugins/dataTables.bootstrap.css',
        'vendor/datatables-responsive/dataTables.responsive.css',
        'dist/css/sb-admin-2.css',
        'vendor/font-awesome/css/font-awesome.min.css',
        'vendor/morrisjs/morris.css',
    ];
    public $js = [
//        'vendor/bootstrap/js/bootstrap.min.js',
        'vendor/metisMenu/metisMenu.min.js',
        'vendor/datatables/js/jquery.dataTables.min.js',
        'vendor/datatables-plugins/dataTables.bootstrap.min.js',
        'vendor/datatables-responsive/dataTables.responsive.js',
        'js/yii2AjaxRequest.js',
        'dist/js/sb-admin-2.js',

    ];
    public $depends = [
//        'rmrevin\yii\fontawesome\NpmFreeAssetBundle',
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        '\rmrevin\yii\fontawesome\AssetBundle'
    ];
}