<?php

namespace app\controllers;

use app\models\Brands;
use app\models\Categories;
use app\models\CategoriesSearch;
use app\models\Colors;
use app\models\FavoritesProducts;
use app\models\Products;
use app\models\ProductsSearch;
use app\models\Queries;
use app\models\Seasons;
use app\models\Sizes;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\sphinx\Query;

class CategoriesController extends \yii\web\Controller
{
    public $layout = 'category-layout';

    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param string|null $category_alias
     */
    public function actionView(string $category_alias = null, array $categories = null)
    {
        $cat = [];
        if ($categories !== null) {
            if ($category_alias) {
                $get = get();
                $get[0] = 'categories/view';
                unset ($get['category_alias']);

                return $this->redirect(Url::to($get));
            }

            // Ищем все категории
            $allCategories = (new Categories)->getAllCategories();

            foreach ($categories as $category_id) {
                $categoryInfo = (new Categories)->getCategoryInfo(false, $category_id);
                // Ищем всех предков и подкатегории
                $categoryParentsAndChildren = (new Categories)->getParentsAndChildren($allCategories, $categoryInfo);

                foreach ($categoryParentsAndChildren['childrens'] as $item) {
                    if ((int)$item['lft'] === ((int)$item['rgt'] - 1)) {
                        $cat[] = $item['id'];
                    }
                }
            }
        } else {
            if (Yii::$app->request->isAjax) {
                $category_alias = Yii::$app->request->get('category_alias');
            }

            $categoryInfo = (new Categories)->getCategoryInfo($category_alias, null, Yii::$app->request->queryParams['all'] ?? null);

            if ($category_alias) {
                // Ищем все категории
                $allCategories = (new Categories)->getAllCategories();

                // Ищем всех предков и подкатегории
                $categoryParentsAndChildren = (new Categories)->getParentsAndChildren($allCategories, $categoryInfo);

                foreach ($categoryParentsAndChildren['childrens'] as $item) {
                    if ((int)$item['lft'] === ((int)$item['rgt'] - 1)) {
                        $cat[] = $item['id'];
                    }
                }
            }
        }

        $favoritesProducts = (new FavoritesProducts)->favoritesProductsList();
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $cat);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_product_list', [
                'dataProvider' => $dataProvider,
                'favoritesProducts' => $favoritesProducts,
            ]);
        }

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'categoryInfo' => $categoryInfo,
            'parentsCategories' => isset($categoryParentsAndChildren) ? $categoryParentsAndChildren['parents'] : false,
            'favoritesProducts' => $favoritesProducts,
        ]);
    }

    /**
     * @param string|null $search
     * @return string
     */
    public function actionSearch(string $search = null): string
    {
        // Получение всех id товаров соответствующих поиску
        $productIDs = $this->sphinxGetPosition($search);

        // Сохраниение поискового запроса в базу
        $this->sphinxSaveRequest($search);

        if (!empty($productIDs)) {
            $searchModel = new ProductsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, false, $productIDs);
        }

        $favoritesProducts = (new FavoritesProducts)->favoritesProductsList();

        return $this->render(
            'search',
            [
                'dataProvider' => $dataProvider ?? false,
                'favoritesProducts' => $favoritesProducts
            ]
        );

    }

    /**
     * @param $search
     * @return array|false
     */
    public function sphinxGetPosition($search)
    {
        $query = new Query;
        $productIDs = false;

        $resultIDs = $query->from('test1')
            ->match($search)
            ->limit(100000)
            ->all();

        if (!empty($resultIDs)) {
            $productIDs = array_column($resultIDs, 'id');
        }

        return $productIDs;
    }

    /**
     * @param $search
     */
    public function sphinxSaveRequest($search): void
    {
        $queried = Queries::find()
            ->where(['queries_text' => trim($search)])
            ->limit(1)
            ->one();

        if (!empty($queried)) {
            ++$queried->queries_count;
        } else {
            $queried = new Queries();
            $queried->queries_text = trim($search);
        }
        $queried->save();
    }

    /**
     * @return string
     */
    public function actionSearchQueries(): string
    {
        $queriesList = Queries::find()
            ->select(['queries_text', 'queries_count', 'id'])
            ->orderBy(['queries_count' => SORT_DESC])
            ->asArray()
            ->all();

        return Json::encode($queriesList);
    }

    /**
     * @return string
     */
    public function actionFilterSort(): string
    {
        $this->layout = false;

        return $this->render('filter-sort');
    }


    /**
     * @return string
     */
    public function actionFilterFloor(): string
    {
        $categoryInfo = (new Categories)->getCategoryInfo(Yii::$app->request->get('category_alias'));

        // Ищем все категории
        $allCategories = (new Categories)->getAllCategories();

        // Ищем всех предков и подкатегории
        $categoryParentsAndChildren = (new Categories)->getParentsAndChildren($allCategories, $categoryInfo);

        $floor_list = [
            ['all' => 'me', 'name' => 'Товары <b>моих</b> размеров'],
            ['all' => 'man', 'name' => 'Мужские товары'],
            ['all' => 'woman', 'name' => 'Женские товары'],
            ['all' => 'all', 'name' => 'Мужские и женские товары'],
        ];

        foreach ($floor_list as $id => $item) {
            $get = [
                'seasons' => Yii::$app->request->get('seasons'),
                'brands' => Yii::$app->request->get('brands'),
                'sizes' => Yii::$app->request->get('sizes'),
                'colors' => Yii::$app->request->get('colors'),
                'product_price_from' => Yii::$app->request->get('product_price_from'),
                'product_price_to' => Yii::$app->request->get('product_price_to'),
                'all' => $item['all'],
                'search' => Yii::$app->request->get('search')
            ];

            if ($search = Yii::$app->request->get('search')) {
                // Получение всех id товаров соответствующих поиску
                $productIDs = $this->sphinxGetPosition($search);
            }

            $searchModel = new ProductsSearch();
            $dataProvider = $searchModel->search(array_filter($get), array_column($categoryParentsAndChildren['childrens'], 'id'), $productIDs ?? false);

            $floor_list[$id]['count'] = $dataProvider->getTotalCount();
        }

        return $this->renderAjax('filter-floor', ['floor_list' => $floor_list]);
    }


    /**
     * @return string
     */
    public function actionFilterCategories(): string
    {
        $categoryInfo = (new Categories)->getCategoryInfo(Yii::$app->request->get('category_alias'));

        return $this->renderAjax('filter-categories', [
            'categoryInfo' => $categoryInfo,
            'getParamName' => 'categories',
        ]);
    }

    /**
     * @return string
     */
    public function actionFilterColor(): string
    {
        $categoryParentsAndChildren = $this->categoryParentsAndChildren();

        $checkboxList = $this->checkboxListCount(Colors::find()->indexBy('id')->orderBy('color_name')->all(), 'colors', $categoryParentsAndChildren);

        return $this->renderAjax('filter-modal', [
            'checkboxList' => $checkboxList,
            'getParam' => Yii::$app->request->get('colors'),
            'getParamName' => 'colors',
            'attrName' => 'color_name',
            'idAttrName' => 'color',
        ]);
    }

    /**
     * @return string
     */
    public function actionFilterSize(): string
    {
        $categoryParentsAndChildren = $this->categoryParentsAndChildren();

        $checkboxList = $this->checkboxListCount(Sizes::find()->indexBy('id')->orderBy('size_for')->orderBy('size_for, size_name')->all(), 'sizes', $categoryParentsAndChildren);

        return $this->renderAjax('filter-modal', [
            'checkboxList' => $checkboxList,
            'getParam' => Yii::$app->request->get('sizes'),
            'getParamName' => 'sizes',
            'attrName' => 'size_name',
            'idAttrName' => 'size',
        ]);
    }

    /**
     * @return string
     */
    public function actionFilterBrand(): string
    {
        $categoryParentsAndChildren = $this->categoryParentsAndChildren();

        $checkboxList = $this->checkboxListCount(Brands::find()->indexBy('id')->orderBy('brand_name')->all(), 'brands', $categoryParentsAndChildren);

        return $this->renderAjax('filter-modal', [
            'checkboxList' => $checkboxList,
            'getParam' => Yii::$app->request->get('brands'),
            'getParamName' => 'brands',
            'attrName' => 'brand_name',
            'idAttrName' => 'brand',
        ]);
    }

    /**
     * @return string
     */
    public function actionFilterSeason(): string
    {
        $categoryParentsAndChildren = $this->categoryParentsAndChildren();

        $checkboxList = $this->checkboxListCount(Seasons::find()->indexBy('id')->orderBy('season_name')->all(), 'seasons', $categoryParentsAndChildren);

        return $this->renderAjax('filter-modal', [
            'checkboxList' => $checkboxList,
            'getParam' => Yii::$app->request->get('seasons'),
            'getParamName' => 'seasons',
            'attrName' => 'season_name',
            'idAttrName' => 'season',
        ]);
    }

    /**
     * @return string
     */
    public function actionFilterPrice(): string
    {
        $categoryInfo = (new Categories)->getCategoryInfo(Yii::$app->request->get('category_alias'));

        // Ищем все категории
        $allCategories = (new Categories)->getAllCategories();

        // Ищем всех предков и подкатегории
        $categoryParentsAndChildren = (new Categories)->getParentsAndChildren($allCategories, $categoryInfo);

        if ($search = Yii::$app->request->get('search')) {
            // Получение всех id товаров соответствующих поиску
            $productIDs = $this->sphinxGetPosition($search);
        }

        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(array_filter(Yii::$app->request->get()), array_column($categoryParentsAndChildren['childrens'], 'id'), $productIDs ?? false);

        return $this->renderAjax('filter-price', [
            'dataProvider' => $dataProvider,
        ]);
    }

    private function categoryParentsAndChildren(): array
    {
        $cat = [];

        if (Yii::$app->request->get('categories') !== null && !empty(array_filter(Yii::$app->request->get('categories')))) {
            // Ищем все категории
            $allCategories = (new Categories)->getAllCategories();

            foreach (array_filter(Yii::$app->request->get('categories')) as $category_id) {
                $categoryInfo = (new Categories)->getCategoryInfo(false, $category_id);

                // Ищем всех предков и подкатегории
                $categoryParentsAndChildren = (new Categories)->getParentsAndChildren($allCategories, $categoryInfo);

                foreach ($categoryParentsAndChildren['childrens'] as $item) {
                    if ((int)$item['lft'] === ((int)$item['rgt'] - 1)) {
                        $cat[$item['id']] = $item['id'];
                    }
                }
            }
        } else {
            $categoryInfo = (new Categories)->getCategoryInfo(Yii::$app->request->get('category_alias'));

            // Ищем все категории
            $allCategories = (new Categories)->getAllCategories();

            // Ищем всех предков и подкатегории
            $categoryParentsAndChildren = (new Categories)->getParentsAndChildren($allCategories, $categoryInfo);

            foreach ($categoryParentsAndChildren['childrens'] as $item) {
                if ((int)$item['lft'] === ((int)$item['rgt'] - 1)) {
                    $cat[$item['id']] = $item['id'];
                }
            }
        }

        return $cat;
    }


    private function checkboxListCount(array $checkboxList, $checkboxName, $categoryParentsAndChildren): array
    {
        foreach ($checkboxList as $item) {
            $get = [
                'colors' => Yii::$app->request->get('colors'),
                'sizes' => Yii::$app->request->get('sizes'),
                'brands' => Yii::$app->request->get('brands'),
                'product_price_from' => Yii::$app->request->get('product_price_from'),
                'product_price_to' => Yii::$app->request->get('product_price_to'),
                'seasons' => Yii::$app->request->get('seasons'),
                'all' => Yii::$app->request->get('all'),
                'search' => Yii::$app->request->get('search')
            ];

            $get[$checkboxName] = [$item->id];

            if ($search = Yii::$app->request->get('search')) {
                // Получение всех id товаров соответствующих поиску
                $productIDs = $this->sphinxGetPosition($search);
            }

            $searchModel = new ProductsSearch();
            $dataProvider = $searchModel->search(array_filter($get), $categoryParentsAndChildren, $productIDs ?? false);

            $item->count = $dataProvider->getTotalCount();
        }

        return $checkboxList;
    }


}


