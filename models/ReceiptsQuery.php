<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Receipts]].
 *
 * @see Receipts
 */
class ReceiptsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Receipts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Receipts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
