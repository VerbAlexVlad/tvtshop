<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProductColors]].
 *
 * @see ProductColors
 */
class ProductColorsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ProductColors[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductColors|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
