<?php

namespace app\widgets;

use app\models\ProductsSearch;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class CategoriesList extends Widget
{
    public $tpl;
    public $menuHtml;
    public $tree;
    public $categoryInfo;
    public $all;
    public $categories;

    public function init()
    {
        parent::init();
        if ($this->tpl === null) {
            $this->tpl = 'menu';
        }
        $this->tpl .= '.php';
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $get = array_filter([
            'colors' => !empty(Yii::$app->request->get('colors')) ? implode("_", Yii::$app->request->get('colors')) : false,
            'brands' => !empty(Yii::$app->request->get('brands')) ? implode("_", Yii::$app->request->get('brands')) : false,
            'seasons' => !empty(Yii::$app->request->get('seasons')) ? implode("_", Yii::$app->request->get('seasons')) : false,
            'all' => $this->all ?? Yii::$app->request->get('all'),
            'sizes' => !empty(Yii::$app->request->get('sizes')) ? implode("_", Yii::$app->request->get('sizes')) : false,
            'product_price_from' => Yii::$app->request->get('product_price_from') ?? false,
            'product_price_to' => Yii::$app->request->get('product_price_to') ?? false,
            'categories' => Yii::$app->request->get('categories') ? implode("_", Yii::$app->request->get('categories')) : false,
            'tpl' => $this->tpl,
        ]);

        $dirNameArr = [];
        foreach ($get as $id => $item) {
            $dirNameArr[] = "{$id}-{$item}";
        }

        $dirName = implode("_", $dirNameArr);

        $cache = Yii::$app->cache;
        $cache_path = Url::to(["@runtime/cache/menu/{$dirName}/"]);
        $cache->cachePath = $cache_path;
        $this->tree = $cache->get($dirName);

        if ($this->tree === false) {
            $this->tree = (new \app\models\Categories)->getAllCategoriesInTreeView();

            if(Yii::$app->request->get('categories') !== null && !empty(array_filter(Yii::$app->request->get('categories')))){
                $this->filteringCategories($this->tree->children);
            }

            $this->findCountProducts($this->tree->children);

            $cache->set($dirName, $this->tree, 60 * 60 * 6);
        }

        $this->menuHtml = $this->getMenuHtml($this->tree->children);

        return $this->menuHtml;
    }


    /**
     * Если есть GET['categories], то удаляем те категории, которых нет в массиве
     * @param $tree
     * @param string $tab
     */
    protected function filteringCategories($tree): void
    {
        foreach ($tree as $category) {
            if (isset($category->children) && !empty($category->children)) {
                $this->filteringCategories($category->children);
            } else if (($category->lft === $category->rgt - 1) && !in_array($category->id, Yii::$app->request->get('categories'))) {
                $category->hidden = true;
            }
        }
    }


    /**
     * @param $tree
     * @param string $tab
     * @return int
     */
    protected function findCountProducts($tree): int
    {
        $count = 0;

        foreach ($tree as $category) {

            if (isset($category->children) && !empty($category->children)) {
                $category->count += $this->findCountProducts($category->children);
            } else {
                $searchModel = new ProductsSearch();
                if ($this->tpl === 'filter-categories.php') {
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $category->id);
                    $category->count = $dataProvider->getTotalCount();
                } elseif ($this->tpl === 'mmenu.php' && !$category->hidden) {
                    $params = ['all' => $this->all];
                    $dataProvider = $searchModel->search($params, $category->id);
                    $category->count = $dataProvider->getTotalCount();
                } elseif (!$category->hidden) {
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $category->id);
                    $category->count = $dataProvider->getTotalCount();
                }
            }
            $count += $category->count;
        }

        return $count;
    }

    /**
     * @param $tree
     * @param false $start
     * @param false $depth
     * @param string $tab
     * @return string
     */
    protected function getMenuHtml($tree, int $start = null, int $depth = null, string $tab = ''): string
    {
        $str = '';

        foreach ($tree as $category) {
            if ((int)$category->count !== 0) {
                $str .= $this->catToTemplate($category, $tab, $start, $depth);
            }
        }
        return $str;
    }

    protected function catToTemplate($category, string $tab = '', int $start = null, int $depth = null)
    {
        ob_start();
        include __DIR__ . '/categpries_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}