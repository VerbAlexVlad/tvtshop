<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Userparams;

/**
 * UserparamsSearch represents the model behind the search form of `app\models\Userparams`.
 */
class UserparamsSearch extends Userparams
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userparam_parameters_id', 'userparam_param_num'], 'integer'],
            [['userparam_value'], 'number'],
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
        $query = Userparams::find();

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
            'userparam_parameters_id' => $this->userparam_parameters_id,
            'userparam_value' => $this->userparam_value,
            'userparam_param_num' => $this->userparam_param_num,
        ]);

        return $dataProvider;
    }
}
