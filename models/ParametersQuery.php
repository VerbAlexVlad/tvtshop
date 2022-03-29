<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Parameters]].
 *
 * @see Parameters
 */
class ParametersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Parameters[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Parameters|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
