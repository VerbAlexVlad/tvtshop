<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2021
 * @version   2.0.6
 */

namespace app\base;

use kartik\base\BootstrapTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\View;
use kartik\select2\AssetBundle as kartikAssetBundle;

/**
 * Asset bundle used for all Krajee extensions with bootstrap and jquery dependency.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
class AssetBundle extends kartikAssetBundle
{
    use BootstrapTrait;

    /**
     * @var bool whether to enable the dependency with yii2 bootstrap asset bundle (depending on [[bsVersion]])
     */
    public $bsDependencyEnabled = 111;

    /**
     * @var bool whether the bootstrap JS plugins are to be loaded and enabled
     */
    public $bsPluginEnabled = false;

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!isset($this->bsDependencyEnabled)) {
            $this->bsDependencyEnabled = ArrayHelper::getValue(Yii::$app->params, 'bsDependencyEnabled', true);
        }

        parent::init();
    }


    /**
     * Registers this asset bundle with a view after validating the bootstrap version
     * @param View $view the view to be registered with
     * @param string $bsVer the bootstrap version
     * @return static the registered asset bundle instance
     */
    public static function registerBundle($view, $bsVer = null)
    {
        $currVer = ArrayHelper::getValue(Yii::$app->params, 'bsVersion', null);
        if (empty($bsVer) || static::isSameVersion($currVer, $bsVer)) {
            return static::register($view);
        }
        Yii::$app->params['bsVersion'] = $bsVer;
        $out = static::register($view);
        if (!empty($currVer)) {
            Yii::$app->params['bsVersion'] = $currVer;
        }
        return $out;
    }
}
