<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[FavoritesShops]].
 *
 * @see FavoritesShops
 */
class FavoritesShopsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return FavoritesShops[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return FavoritesShops|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
