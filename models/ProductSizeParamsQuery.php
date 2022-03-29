<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProductSizeParams]].
 *
 * @see ProductSizeParams
 */
class ProductSizeParamsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ProductSizeParams[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductSizeParams|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
