<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProductSizes]].
 *
 * @see ProductSizes
 */
class ProductSizesQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere('[[ps_status]]=1');
    }

    /**
     * {@inheritdoc}
     * @return ProductSizes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProductSizes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
