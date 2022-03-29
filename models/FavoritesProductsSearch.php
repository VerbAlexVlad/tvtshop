<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FavoritesProductsSearch represents the model behind the search form of `app\models\FavoritesProducts`.
 */
class FavoritesProductsSearch extends FavoritesProducts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fp_user_id', 'fp_product_id'], 'integer'],
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
    public function search($favorites_products)
    {
        $query = FavoritesProducts::find()
            ->joinWith([
                'fpProduct' => function ($fpProduct) use ($favorites_products) {
                    $fpProduct->andWhere([
                        'in', 'products.id', $favorites_products
                    ]);
                    $fpProduct->with([
                        'productModel' => function ($productModel) {
                            $productModel->with([
                                'category',
                                'brand',
                            ]);
                        },
                        'productColors' => function ($productModel) {
                            $productModel->with([
                                'color'
                            ]);
                        },
                    ]);
                }
            ]);
dbg($query);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'fp_user_id' => $this->fp_user_id,
//            'fp_product_id' => $this->fp_product_id,
//        ]);

        return $dataProvider;
    }
}
