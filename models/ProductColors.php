<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_colors}}".
 *
 * @property int $product_id
 * @property int $color_id
 *
 * @property Products $product
 * @property Colors $color
 */
class ProductColors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_colors}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'color_id'], 'required'],
            [['product_id', 'color_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['color_id'], 'exist', 'skipOnError' => true, 'targetClass' => Colors::class, 'targetAttribute' => ['color_id' => 'id']],
        ];
    }

    public static function primaryKey()
    {
        return ['product_id', 'color_id'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product ID'),
            'color_id' => Yii::t('app', 'Color ID'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Color]].
     *
     * @return \yii\db\ActiveQuery|ColorQuery
     */
    public function getColor()
    {
        return $this->hasOne(Colors::class, ['id' => 'color_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductColorsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductColorsQuery(get_called_class());
    }
}
