<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Sities]].
 *
 * @see Sities
 */
class SitiesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Sities[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Sities|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
