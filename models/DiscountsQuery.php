<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Discounts]].
 *
 * @see Discounts
 */
class DiscountsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Discounts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Discounts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
