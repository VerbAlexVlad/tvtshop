<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Providers]].
 *
 * @see Providers
 */
class ProvidersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Providers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Providers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
