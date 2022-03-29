<?php

namespace app\models;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 */
class Discount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%discounts}}';
    }
  
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['id' => 'discount']);
    }  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'unit', 'from_date', 'to_date'], 'required'],
            [['name_discount'], 'string', 'max' => 50],
            [['value'], 'double'],
            [['unit'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name_discount' => \Yii::t('app', 'Наименование скидки'),
            'value' => \Yii::t('app', 'Значение'),
            'unit' => \Yii::t('app', 'Единица измерения'),
            'from_date' => \Yii::t('app', 'От'),
            'to_date' => \Yii::t('app', 'До')
        ];
    }
}
