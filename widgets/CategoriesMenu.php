<?php

namespace app\widgets;

use app\models\ProductsSearch;
use yii\base\Widget;
use Yii;

class CategoriesMenu extends Widget
{
    public $tpl;
    public $menuHtml;
    public $tree;
    public $categoryInfo;
    public $all;

    public function init()
    {
        parent::init();
        if ($this->tpl === null) {
            $this->tpl = 'menu';
        }
        $this->tpl .= '.php';
    }

    public function run()
    {
        $this->tree = (new \app\models\Categories)->getAllCategoriesInTreeView();

        $this->getMenuHtml1($this->tree['children']);

        $this->menuHtml = $this->getMenuHtml($this->tree['children']);

        return $this->menuHtml;
    }

    protected function getMenuHtml1($tree, $tab = '')
    {
        $count = 0;
        foreach ($tree as $id=>$category) {
            if(isset($category->children) && !empty($category->children)) {
                $category->count += $this->getMenuHtml1($category->children);
            } else {
                $searchModel = new ProductsSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $category->id);


                $category->count = $dataProvider->getTotalCount();
            }
            $count += $category->count;
        }
        return $count;
    }

    protected function getMenuHtml($tree, $tab = '')
    {
        $str = '';
        foreach ($tree as $category) {
            if($category->count !== 0){
                $str .= $this->catToTemplate($category, $tab);
            }
        }
        return $str;
    }

    protected function catToTemplate($category, $tab)
    {
        ob_start();
        include __DIR__ . '/categpries_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}