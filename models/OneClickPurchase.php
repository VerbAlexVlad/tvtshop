<?php

namespace app\models;

/**
 * This is the model class for table "OneClickPurchase".
 *
 * @property integer $id
 * @property string $color
 */
class OneClickPurchase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%one_click_purchase}}';
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['id' => 'one_click_purchase_product_id']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['one_click_purchase_product_id', 'one_click_purchase_phone'], 'required'],
            [['one_click_purchase_product_id'], 'integer'],
            [['one_click_purchase_phone'], 'string'],
        ];
    }

}
