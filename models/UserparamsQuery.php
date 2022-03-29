<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Userparams]].
 *
 * @see Userparams
 */
class UserparamsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Userparams[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Userparams|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
