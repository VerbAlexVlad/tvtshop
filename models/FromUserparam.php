<?php

namespace app\models;
use \rico\yii2images\models\Image;

/**
 * Class FromUserparam
 * @package app\models
 */
class FromUserparam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%from_userparam}}';
    }

    public static function primaryKey()
    {
        return ['product_id'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainImage()
    {
        return $this->hasOne(Image::class, ['itemId' => 'product_id'])->where(['image.modelName' => "Products", 'image.isMain' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShops()
    {
        return $this->hasOne(Shops::class, ['id' => 'shops_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParam()
    {
        return $this->hasMany(Param::class, ['product_param_id' => 'param_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dimension_ruler_id', 'product_id', 'param_id', 'floor', 'shops_id', 'status', 'category_id'], 'integer'],
            [['remains'], 'double'],
        ];
    }
}
