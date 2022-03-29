<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Deleted]].
 *
 * @see Deleted
 */
class DeletedQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Deleted[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Deleted|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
