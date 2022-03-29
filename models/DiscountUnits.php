<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%discount_units}}".
 *
 * @property int $id
 * @property string $discount_units_name
 *
 * @property Discount[] $discounts
 */
class DiscountUnits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%discount_units}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discount_units_name'], 'required'],
            [['discount_units_name'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'discount_units_name' => Yii::t('app', 'Discount Units Name'),
        ];
    }

    /**
     * Gets query for [[Discounts]].
     *
     * @return \yii\db\ActiveQuery|DiscountQuery
     */
    public function getDiscounts()
    {
        return $this->hasMany(Discount::class, ['discount_unit_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return DiscountUnitsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DiscountUnitsQuery(get_called_class());
    }
}
