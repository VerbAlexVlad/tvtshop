<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Keywords]].
 *
 * @see Keywords
 */
class KeywordsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Keywords[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Keywords|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
