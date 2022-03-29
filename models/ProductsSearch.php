<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * ProductsSearch represents the model behind the search form of `app\models\Products`.
 */
class ProductsSearch extends Products
{
    public $brands;
    public $sizes;
    public $colors;
    public $seasons;
    public $product_price_from;
    public $product_price_to;
    public $all;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'product_status', 'product_floor', 'product_season', 'product_model_id', 'product_shop_id', 'product_discount', 'product_description_id'], 'integer'],
            [['product_price', 'product_price_from', 'product_price_to', 'product_price_wholesale'], 'number'],
            [['product_datecreate', 'product_alias'], 'safe'],
            [['brands', 'sizes', 'colors'], 'filter', 'filter' => function ($value) {
                if ($value) {
                    foreach ($value as $id => $item) {
                        $value[$id] = (int)$item;
                    }
                }
                return $value;
            }],
            [['all'], 'filter', 'filter' => function ($value) {
                $floors = [0, 1, 2];

                if ($value) {
                    switch ($value) {
                        case 'man':
                            $floors = [0, 1];
                            break;
                        case 'woman':
                            $floors = [0, 2];
                            break;
                        case 'me':
                            if (!Yii::$app->user->isGuest) {
                                $floors = [0, Yii::$app->request->user->floor];
                            } else if (isset($_SESSION['user']['floor']) && !empty($_SESSION['user']['floor'])) {
                                $floors = [0, $_SESSION['user']['floor']];
                            } else {
                                return false;
                            }
                    }
                }
                return $floors;
            }],
            [['seasons'], 'filter', 'filter' => function ($value) {
                if ($value) {
                    foreach ($value as $item) {
                        switch ($item) {
                            case 1:
                            case 2:
                            case 3:
                            case 4:
                                $seasons[] = $item;
                        }
                    }
                    if (empty(array_filter($seasons))) {
                        $seasons = [1, 2, 3, 4];
                    }

                    return $seasons;
                }
            }]
        ];
    }

    /**
     * @return array|array[]
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return array|Products
     * @throws NotFoundHttpException
     */
    public function searchProduct($params)
    {
        $params['ProductsSearch'] = $params;
        $this->load($params);
        if (!$this->validate()) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
//
//        $query = Products::find()
//            ->select(["`products`.`id` as id", "CONCAT(
//                COALESCE(`seasons`.`season_name`, ''),
//                ' ',
//                COALESCE(`categories`.`name`, ''),
//                ' ',
//                COALESCE(`brands`.`brand_name`, ''),
//                ' ',
//                COALESCE(`floor`.`floor_name`, ''),
//                ' ',
//                COALESCE(`colors`.`color_name`, ''),
//                ' ',
//                COALESCE(`characteristics`.`characteristic_name`, ''),
//                ' ',
//                COALESCE(`products_characteristics`.`ph_volume`, '')
//            ) as text"])
//            ->joinWith([
//                'productsCharacteristics.phCharacteristic',
//                'productModel' => function ($productModel) {
//                    $productModel->joinWith(['category', 'brand']);
//                },
//                'productSizes' => function ($productSizes) {
//                    $productSizes->joinWith(['psSize']);
//                },
//                'productsSeason',
//                'productColors.color',
//                'floor',
//            ]);
//        dbg($query);



        $query = Products::find()
            ->where(['product_alias' => $this->product_alias])
            ->joinWith([
                'productModel' => function ($productModel) {
                    $productModel->with('category', 'brand');
                },
                'productSizes' => function ($productSizes) {
                    $productSizes->with(['psSize']);
                    if ($userParam = (new Userparams())->getUserParamId()) {
                        $productSizes->with([
                            'params' => function ($params) use ($userParam) {
                                $params->joinWith([
                                    'paramUserparam' => function ($paramUserparam) use ($userParam) {
                                        $paramUserparam->andWhere(['userparam_id' => $userParam]);
                                    }
                                ]);
                            },
                        ]);
                    }
                    $productSizes->indexBy('ps_size_id');
                },
                'productDiscount' => function ($productDiscount) {
                    $productDiscount->with('discountUnit');
                },
                'productShop' => function ($productShop) {
                    $productShop->with('deliveries', 'payments');
                },
                'productsSeason',
                'productColors.color',
                'images',
                'productDescription',
                'floor',
            ])
            ->distinct()
            ->limit(1)
            ->one();

        if (empty($query)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        return $query;
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return array|Products
     * @throws NotFoundHttpException
     */
    public function searchProductInfoForOrderOneClick($product_alias)
    {
        $query = Products::find()
            ->where(['product_alias' => $product_alias])
            ->with([
                'productSizes' => function ($productSizes) {
                    $productSizes->joinWith(['psSize']);
                        $productSizes->with([
                            'params' => function ($params) {
                                $params->with([
                                    'paramUserparam' => function ($paramUserparam) {
                                        if($userParam = (new Userparams())->getUserParamId()){
                                            $paramUserparam->andWhere(['userparam_id' => $userParam]);
                                        }
                                    },
                                ]);
                                $params->joinWith([
                                    'paramParameters'
                                ]);
                            },
                        ]);
                    $productSizes->indexBy('id');
                    $productSizes->groupBy('sizes.size_name');
                },
            ])
            ->distinct()
            ->limit(1)
            ->one();

        if (empty($query)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        return $query;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return array|Products
     * @throws NotFoundHttpException
     */
    public function searchProductSizes($parameters)
    {
        $params['ProductsSearch'] = $parameters;
        $this->load($params);
        if (!$this->validate()) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $query = Products::find()
            ->where(['product_alias' => $this->product_alias])
            ->joinWith([
                'productSizes' => function ($productSizes) {
                    $productSizes->with(['psSize']);
                    if ($userParam = (new Userparams())->getUserParamId()) {
                        $productSizes->with([
                            'params' => function ($params) use ($userParam) {
                                $params->joinWith([
                                    'paramUserparam' => function ($paramUserparam) use ($userParam) {
                                        $paramUserparam->andWhere(['userparam_id' => $userParam]);
                                    }
                                ]);
                            },
                        ]);
                    }
                    $productSizes->indexBy('ps_size_id');
                },
            ])
            ->distinct()
            ->limit(1)
            ->one();

        if (empty($query)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        return $query;
    }


    /**
     * @param false $params
     * @param false $subcategories
     * @param false $productsId
     * @param false $allSizes
     * @return ActiveDataProvider
     */
    public function search($params = false, $subcategories = false, $productsId = false, $allSizes = false): ActiveDataProvider
    {
        $query = Products::find()
            ->select([
                'products.id',
                'product_alias',
                'product_floor',
                'product_model_id',
                'product_price',
                'product_season',
                'product_status',
                'product_discount',
                'product_views'
            ])
            ->active()
            ->joinWith([
                'productModel' => function ($productModel) use ($subcategories) {
//                    $arrayCat = [];
//                $arrayCat = is_array($subcategories) ? array_column($subcategories, 'id') ? [$subcategories];
                    if ($subcategories) {
                        $productModel->andWhere(['in', 'category_id', $subcategories]);
                    }
                    $productModel->joinWith([
                        'category' => function ($category) {
                            $category->select(['id', 'name', 'name_singular_category']);
//                            $category->joinWith([
//                                'categoriesParameters' => function ($categoriesParameters) {
//                                    $categoriesParameters->joinWith([
//                                        'cpParameter' => function ($cpParameter) {
//                                            $cpParameter->andWhere(['parameter_type' => 1]);
//                                        }
//                                    ]);
//                                    $categoriesParameters->indexBy('cp_parameter_id');
//                                }
//                            ]);
                        },
                        'brand'
                    ]);
                },
                'productSizes' => function ($productSizes) use ($allSizes, $params) {
                    $productSizes->active();
                    if ($allSizes == false) {
                        $productSizes->with(['psSize']);
                    }

                    if (isset($params['all']) && $params['all'] == 'me' && $userParam = (new Userparams)->getUserParamId()) {
                        $productSizes->joinWith([
                            'params' => function ($params) use ($userParam) {
                                $params->joinWith([
                                    'paramUserparam' => function ($paramUserparam) use ($userParam) {
                                        $paramUserparam->andWhere([
                                            'userparam_id' => $userParam
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    }
                },
                'productDiscount' => function ($productDiscount) {
                    $productDiscount->with('discountUnit');
                },
                'productColors' => function ($productColors) use ($params) {
                    if (isset($params['colors']) && !empty($params['colors'])) {
                        $productColors->joinWith(['color']);
                    }
                },
                'image',
            ])
            ->distinct();

        if ($productsId) {
            $query = $query->andWhere(['in', 'products.id', $productsId]);
        }

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 36,
            ],
            'sort' => [
                'defaultOrder' => [
                    'product_views' => SORT_DESC,
                    'id' => SORT_ASC,
                ],
                'attributes' => [
                    'id',
                    'product_price',
                    'product_views',
                ],
            ]
        ]);

        $params['ProductsSearch'] = $params;
        $this->load((array)$params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
//            'product_sizes.ps_size_id' => $this->sizes,
//            'models.brand_id' => $this->brands,
            'product_floor' => $this->product_floor,
//            'product_shop_id' => $this->product_shop_id,
            'product_discount' => $this->product_discount,
        ]);

        if (!empty($this->all)) {
            $query->andFilterWhere(['in', 'products.product_floor', $this->all]);
        }

        if (!empty($this->sizes)) {
            $query->andFilterWhere(['in', 'product_sizes.ps_size_id', $this->sizes]);
        }

        if (!empty($this->brands)) {
            $query->andFilterWhere(['in', 'models.brand_id', $this->brands]);
        }

        if (!empty($this->colors)) {
            $query->andFilterWhere(['in', 'product_colors.color_id', $this->colors]);
        }

        if (!empty($this->seasons)) {
            $query->andFilterWhere(['in', 'products.product_season', $this->seasons]);
        }

        $query->andFilterWhere([
            'and',
            ['>=', "product_price", $this->product_price_from],
            ['<=', "product_price", $this->product_price_to],
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchFavorites()
    {
        $query = Products::find()
            ->select([
                'products.id',
                'product_alias',
                'product_floor',
                'product_model_id',
                'product_price',
                'product_season',
                'product_status',
                'product_discount'
            ])
            ->joinWith([
                'productModel' => function ($productModel) {
                    $productModel->joinWith([
                        'category',
                        'brand'
                    ]);
                },
                'productDiscount' => function ($productDiscount) {
                    $productDiscount->with('discountUnit');
                },
                'productColors.color',
                'image',
            ]);

        if (!Yii::$app->user->isGuest) {
            $query->joinWith([
                'favoritesProducts' => function ($favoritesProducts) {
                    $favoritesProducts->andWhere(['favorites_products.fp_user_id' => Yii::$app->user->id]);
                },
            ]);
        } else {
            $query->andWhere(['in', 'products.id', array_filter($_SESSION['favorites_products'])]);
        }

        $query->distinct();

        // add conditions that should always apply here
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
    }
}
