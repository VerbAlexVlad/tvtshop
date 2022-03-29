<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%products_keys}}".
 *
 * @property int $pk_product_id
 * @property int $pk_key_id
 *
 * @property Product $pkProduct
 * @property Keyword $pkKey
 */
class ProductsKeys extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products_keys}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pk_product_id', 'pk_key_id'], 'required'],
            [['pk_product_id', 'pk_key_id'], 'integer'],
            [['pk_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['pk_product_id' => 'id']],
            [['pk_key_id'], 'exist', 'skipOnError' => true, 'targetClass' => Keyword::class, 'targetAttribute' => ['pk_key_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_product_id' => Yii::t('app', 'Pk Product ID'),
            'pk_key_id' => Yii::t('app', 'Pk Key ID'),
        ];
    }

    /**
     * Gets query for [[PkProduct]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getPkProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'pk_product_id']);
    }

    /**
     * Gets query for [[PkKey]].
     *
     * @return \yii\db\ActiveQuery|KeywordQuery
     */
    public function getPkKey()
    {
        return $this->hasOne(Keyword::class, ['id' => 'pk_key_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductsKeysQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductsKeysQuery(get_called_class());
    }
}
