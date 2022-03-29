<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CategoriesCharacteristics]].
 *
 * @see CategoriesCharacteristics
 */
class CategoriesCharacteristicsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CategoriesCharacteristics[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CategoriesCharacteristics|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
