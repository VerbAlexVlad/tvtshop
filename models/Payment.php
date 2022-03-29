<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%payment}}".
 *
 * @property int $id
 * @property string $payment_name
 * @property string|null $payment_description
 * @property int|null $payment_shop_id
 * @property string|null $payment_requisites
 * @property int $payment_type
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_name'], 'required'],
            [['payment_description', 'payment_requisites'], 'string'],
            [['payment_shop_id', 'payment_type'], 'integer'],
            [['payment_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_name' => Yii::t('app', 'Payment Name'),
            'payment_description' => Yii::t('app', 'Payment Description'),
            'payment_shop_id' => Yii::t('app', 'Payment Shop ID'),
            'payment_requisites' => Yii::t('app', 'Payment Requisites'),
            'payment_type' => Yii::t('app', 'Payment Type'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PaymentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentQuery(get_called_class());
    }
}
