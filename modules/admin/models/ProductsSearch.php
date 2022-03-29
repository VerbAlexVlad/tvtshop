<?php

namespace app\modules\admin\models;

use yii\base\BaseObject;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Products;

/**
 * ProductsSearch represents the model behind the search form of `app\modules\admin\models\Products`.
 */
class ProductsSearch extends Products
{
    public $product_colors;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_views', 'product_status', 'product_floor', 'product_season', 'product_model_id', 'product_shop_id', 'product_discount', 'product_description_id'], 'integer'],
            [['product_price', 'product_price_wholesale'], 'number'],
            [['product_datecreate', 'product_alias', 'product_title', 'product_h1', 'product_colors'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()
            ->joinWith([
                'productModel' => function ($productModel) {
                    $productModel->with('category', 'brand');
                },
                'productSizes' => function ($productSizes) {
                    $productSizes->with(['psSize']);
//                    if ($userParam = (new Userparams())->getUserParamId()) {
//                        $productSizes->with([
//                            'params' => function ($params) use ($userParam) {
//                                $params->joinWith([
//                                    'paramUserparam' => function ($paramUserparam) use ($userParam) {
//                                        $paramUserparam->andWhere(['userparam_id' => $userParam]);
//                                    }
//                                ]);
//                            },
//                        ]);
//                    }
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
            ->distinct();

//        $query = Products::find()
//          ->with([
//            'productSizes'
//          ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'products.id' => $this->id,
            'product_views' => $this->product_views,
            'product_status' => $this->product_status,
            'product_price' => $this->product_price,
            'product_price_wholesale' => $this->product_price_wholesale,
            'product_floor' => $this->product_floor,
            'product_season' => $this->product_season,
            'product_model_id' => $this->product_model_id,
            'product_datecreate' => $this->product_datecreate,
            'product_shop_id' => $this->product_shop_id,
            'product_discount' => $this->product_discount,
            'product_description_id' => $this->product_description_id,
            'product_colors' => $this->product_description_id,
        ]);

        $query->andFilterWhere(['like', 'product_alias', $this->product_alias])
            ->andFilterWhere(['like', 'product_title', $this->product_title])
            ->andFilterWhere(['like', 'product_h1', $this->product_h1]);

        return $dataProvider;
    }
}
