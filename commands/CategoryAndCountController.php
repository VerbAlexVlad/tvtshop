<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Brands;
use app\models\Colors;
use app\models\Products;
use app\models\Seasons;
use Yii;
use yii\console\Controller;
use app\base\BaseFileHelper;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CategoryAndCountController extends Controller
{
    public $tree;

    public function actionIndex()
    {
        $date_today = date("m.d.y"); //присвоено 12.03.15
        $today[1] = date("H:i:s", strtotime("+8 hours")); //присвоит 1 элементу массива 18:32:17
        echo("Интеграция началась в: {$today[1]}, {$date_today}.\n");

        ini_set('max_execution_time', 4000);
        ini_set('memory_limit', -1);

        require('/var/www/www-root/data/www/sadik-mgdn.ru/web/functions.php');

        BaseFileHelper::removeDirectory('/var/www/www-root/data/www/sadik-mgdn.ru/runtime/cache/menu/', $options = [], $delMainDirectory = false);

        $colors = Colors::find()->indexBy('id')->column();
        $brands = Brands::find()->indexBy('id')->column();
        $seasons = Seasons::find()->indexBy('id')->column();

        foreach (['man', 'woman', 'all'] as $all) {
            echo("Генерация для: {$all} .\n");
            $this->cacheAll($all);
            foreach ($colors as $color) {
                $this->cacheAll($all, [$color]);
            }
            foreach ($brands as $brand) {
                $this->cacheAll($all, false, [$brand]);
            }
            foreach ($seasons as $season) {
                $this->cacheAll($all, false, false, [$season]);
            }
        }

//        foreach (['man', 'woman', 'all'] as $all) {
//            foreach ($colors as $color_id => $color) {
//                echo("Цвет: " . $color_id . " из " . count($colors) . " .\n");
//
//                foreach ($brands as $brand) {
//                    $this->cacheAll($all, [$color], [$brand]);
//                    foreach ($seasons as $season) {
//                        $this->cacheAll($all, [$color], [$brand], [$season]);
//                    }
//                }
//            }
//        }

        $date_today = date("m.d.y"); //присвоено 12.03.15
        $today[1] = date("H:i:s", strtotime("+8 hours")); //присвоит 1 элементу массива 18:32:17
        echo("Интеграция закончилась в: {$today[1]}, {$date_today}.\n");
    }

    protected function cacheAll($all, $color = false, $brand = false, $season = false)
    {
        $get = array_filter([
            'colors' => !empty($color) ? implode("_", $color) : false,
            'brands' => !empty($brand) ? implode("_", $brand) : false,
            'seasons' => !empty($season) ? implode("_", $season) : false,
            'all' => $all,
        ]);
        $dirNameArr = [];
        foreach ($get as $id => $item) {
            $dirNameArr[] = "{$id}-{$item}";
        }
        $dirName = implode("_", $dirNameArr);

        $cache = Yii::$app->cache;

        $cache_path = "/var/www/www-root/data/www/sadik-mgdn.ru/runtime/cache/menu/{$dirName}/";
        $cache->cachePath = $cache_path;

        $this->tree = (new \app\models\Categories)->getAllCategoriesInTreeView();
        $this->getMenuHtml($this->tree->children, $all, $color, $brand, $season);

        $cache->set($dirName, $this->tree, 0);
    }

    protected function getMenuHtml($tree, $all, $color = false, $brand = false, $season = false)
    {
        $count = 0;
        foreach ($tree as $category) {
            if (isset($category->children) && !empty($category->children)) {
                $category->count += $this->getMenuHtml($category->children, $all, $color, $brand, $season);
            } else {
                $dataProvider = $this->search(array_filter(['all' => $all, 'colors' => $color, 'brands' => $brand, 'seasons' => $season]), $category->id);
                $category->count = $dataProvider->count();
            }
            $count += $category->count;
        }
        return $count;
    }

    /**
     * @param array $params
     * @param array $subcategories
     */
    public function search($params = false, $subcategories = false)
    {
        $query = Products::find()
            ->select([
                'products.id',
                'product_floor',
                'product_model_id',
                'product_season',
                'product_status',
            ])
            ->active()
            ->joinWith([
                'productModel' => function ($productModel) use ($subcategories) {
                    $productModel->select(['id', 'category_id', 'brand_id']);
                    if ($subcategories) {
                        $productModel->andWhere(['in', 'category_id', $subcategories]);
                    }
                },
                'productColors',
                'image',
            ])
            ->distinct();

        if (!empty($params['all'])) {
            $floors = [0, 1, 2];

            if ($params['all']) {
                switch ($params['all']) {
                    case 'man':
                        $floors = [0, 1];
                        break;
                    case 'woman':
                        $floors = [0, 2];
                        break;
                }
            }
            $query->andFilterWhere(['in', 'products.product_floor', $floors]);
        }

        if (!empty($params['brands'])) {
            $query->andFilterWhere(['in', 'models.brand_id', $params['brands']]);
        }

        if (!empty($params['colors'])) {
            $query->andFilterWhere(['in', 'product_colors.color_id', $params['colors']]);
        }

        if (!empty($params['seasons'])) {
            $query->andFilterWhere(['in', 'products.product_season', $params['seasons']]);
        }

        return $query;
    }
}