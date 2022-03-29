<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Discounts;

/**
 * DiscountsSearch represents the model behind the search form of `app\modules\admin\models\Discounts`.
 */
class DiscountsSearch extends Discounts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'discount_unit_id'], 'integer'],
            [['discount_name', 'discount_from_date', 'discount_to_date'], 'safe'],
            [['discount_value'], 'number'],
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
        $query = Discounts::find();

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
            'discount_value' => $this->discount_value,
            'discount_unit_id' => $this->discount_unit_id,
            'discount_from_date' => $this->discount_from_date,
            'discount_to_date' => $this->discount_to_date,
        ]);

        $query->andFilterWhere(['like', 'discount_name', $this->discount_name]);

        return $dataProvider;
    }
}
