<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProductsList]].
 *
 * @see ProdctsList
 */
class ProductsListQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ProductsList[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductsList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
