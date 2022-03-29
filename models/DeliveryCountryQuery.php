<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DeliveryCountry]].
 *
 * @see DeliveryCountry
 */
class DeliveryCountryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DeliveryCountry[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeliveryCountry|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
