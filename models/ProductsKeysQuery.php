<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProductsKeys]].
 *
 * @see ProductsKeys
 */
class ProductsKeysQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ProductsKeys[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductsKeys|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
