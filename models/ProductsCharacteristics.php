<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%products_characteristics}}".
 *
 * @property int $id
 * @property int $ph_product_id
 * @property int $ph_characteristic_id
 * @property string|null $ph_volume
 *
 * @property Products $phProduct
 * @property Characteristics $phCharacteristic
 */
class ProductsCharacteristics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products_characteristics}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ph_product_id', 'ph_characteristic_id'], 'required'],
            [['ph_product_id', 'ph_characteristic_id'], 'integer'],
            [['ph_volume'], 'string', 'max' => 255],
            [['ph_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['ph_product_id' => 'id']],
            [['ph_characteristic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Characteristics::class, 'targetAttribute' => ['ph_characteristic_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ph_product_id' => Yii::t('app', 'Ph Product ID'),
            'ph_characteristic_id' => Yii::t('app', 'Ph Characteristic ID'),
            'ph_volume' => Yii::t('app', 'Ph Volume'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPhProduct(): ActiveQuery
    {
        return $this->hasOne(Products::class, ['id' => 'ph_product_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPhCharacteristic(): ActiveQuery
    {
        return $this->hasOne(Characteristics::class, ['id' => 'ph_characteristic_id']);
    }

    /**
     * @return ProductsCharacteristicsQuery
     */
    public static function find(): ProductsCharacteristicsQuery
    {
        return new ProductsCharacteristicsQuery(get_called_class());
    }
}
