<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchOrders represents the model behind the search form of `app\models\Orders`.
 */
class SearchOrders extends Orders
{
    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['id', 'order_user_id', 'order_sity_id', 'order_status', 'order_delivery_id', 'order_address_street'], 'integer'],
            [['order_username', 'order_patronymic', 'order_surname', 'order_email', 'order_phone', 'order_adress', 'order_postcode', 'order_date_modification', 'order_comment', 'order_user_ip', 'order_created_at', 'order_updated_at'], 'safe'],
            [['order_price_delivery', 'order_prepayment'], 'number'],
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
        $query = Orders::find();

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
            'order_user_id' => $this->order_user_id,
            'order_sity_id' => $this->order_sity_id,
            'order_date_modification' => $this->order_date_modification,
            'order_status' => $this->order_status,
            'order_price_delivery' => $this->order_price_delivery,
            'order_delivery_id' => $this->order_delivery_id,
            'order_prepayment' => $this->order_prepayment,
            'order_created_at' => $this->order_created_at,
            'order_updated_at' => $this->order_updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_username', $this->order_username])
            ->andFilterWhere(['like', 'order_patronymic', $this->order_patronymic])
            ->andFilterWhere(['like', 'order_surname', $this->order_surname])
            ->andFilterWhere(['like', 'order_email', $this->order_email])
            ->andFilterWhere(['like', 'order_phone', $this->order_phone])
            ->andFilterWhere(['like', 'order_address_street', $this->order_address_street])
            ->andFilterWhere(['like', 'order_adress', $this->order_adress])
            ->andFilterWhere(['like', 'order_postcode', $this->order_postcode])
            ->andFilterWhere(['like', 'order_comment', $this->order_comment])
            ->andFilterWhere(['like', 'order_user_ip', $this->order_user_ip]);

        return $dataProvider;
    }
}
