<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%receipts}}".
 *
 * @property int $id
 * @property int|null $receipt_product_size_id
 * @property int $receipt_count
 * @property int $receipt_provider_id
 * @property string $receipt_delivery_date Дата поставки товара
 * @property int|null $receipt_status
 *
 * @property ProductSize $receiptProductSize
 * @property Provider $receiptProvider
 */
class Receipts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%receipts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'receipt_count', 'receipt_provider_id'], 'required'],
            [['id', 'receipt_product_size_id', 'receipt_count', 'receipt_provider_id', 'receipt_status'], 'integer'],
            [['receipt_delivery_date'], 'safe'],
            [['id'], 'unique'],
            [['receipt_product_size_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductSize::class, 'targetAttribute' => ['receipt_product_size_id' => 'id']],
            [['receipt_provider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::class, 'targetAttribute' => ['receipt_provider_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'receipt_product_size_id' => Yii::t('app', 'Receipt Product Size ID'),
            'receipt_count' => Yii::t('app', 'Receipt Count'),
            'receipt_provider_id' => Yii::t('app', 'Receipt Provider ID'),
            'receipt_delivery_date' => Yii::t('app', 'Дата поставки товара'),
            'receipt_status' => Yii::t('app', 'Receipt Status'),
        ];
    }

    /**
     * Gets query for [[ReceiptProductSize]].
     *
     * @return \yii\db\ActiveQuery|ProductSizeQuery
     */
    public function getReceiptProductSize()
    {
        return $this->hasOne(ProductSize::class, ['id' => 'receipt_product_size_id']);
    }

    /**
     * Gets query for [[ReceiptProvider]].
     *
     * @return \yii\db\ActiveQuery|ProviderQuery
     */
    public function getReceiptProvider()
    {
        return $this->hasOne(Provider::class, ['id' => 'receipt_provider_id']);
    }

    /**
     * {@inheritdoc}
     * @return ReceiptsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReceiptsQuery(get_called_class());
    }
}
