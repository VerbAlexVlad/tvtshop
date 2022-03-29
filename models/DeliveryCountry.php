<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%delivery_country}}".
 *
 * @property int $id
 * @property string|null $dc_country
 */
class DeliveryCountry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%delivery_country}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dc_country'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dc_country' => Yii::t('app', 'Dc Country'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DeliveryCountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DeliveryCountryQuery(get_called_class());
    }
}
