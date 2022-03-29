<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductSizesSearct represents the model behind the search form of `app\models\ProductSizes`.
 */
class ProductSizesSearch extends ProductSizes
{
    public $sizes_in_cart;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ps_product_id', 'ps_size_id', 'ps_param_id', 'ps_status', 'ps_discount_id'], 'integer'],
            [['ps_internal_id'], 'safe'],
            [['sizes_in_cart'], 'filter', 'filter' => function ($value) {
                if ($value) {
                    foreach ($value as $id => $item) {
                        $value[] = (int)$id;
                    }
                }
                return $value;
            }],
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
        $query = ProductSizes::find();

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
            'id' => $this->id,
            'ps_product_id' => $this->ps_product_id,
            'ps_size_id' => $this->ps_size_id,
            'ps_param_id' => $this->ps_param_id,
            'ps_status' => $this->ps_status,
            'ps_discount_id' => $this->ps_discount_id,
        ]);

        $query->andFilterWhere(['like', 'ps_internal_id', $this->ps_internal_id]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchSizesInCart($sizes_in_cart)
    {
        $query = ProductSizes::find()
            ->joinWith([
                'psProduct'=>function($psProduct){
                    $psProduct->with([
                        'productModel'=>function($productModel){
                            $productModel->with([
                                'category',
                                'brand',
                            ]);
                        },
                        'productColors'=>function($productModel){
                            $productModel->with([
                                'color'
                            ]);
                        },
                    ]);
                },
                'psSize'
            ])
            ->indexBy('id');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!empty($this->products_in_cart))
            $query->andFilterWhere(['in', 'product_sizes.id', array_keys($this->sizes_in_cart)]);

        if($sizes_in_cart) {
            foreach ($sizes_in_cart as $id_size_in_cart => $size_in_cart) {
                $query->orFilterWhere(['and',
                    ['product_sizes.id' => (int)$id_size_in_cart],
                    ['product_sizes.ps_product_id' => (int)$size_in_cart['product_id']]
                ]);
            }
        }
        $query->andFilterWhere(['like', 'ps_internal_id', $this->ps_internal_id]);

        return $dataProvider;
    }
}
