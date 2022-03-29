<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%delivery}}".
 *
 * @property int $id
 * @property string $delivery_name
 * @property string|null $delivery_description
 * @property int|null $delivery_country
 * @property string|null $delivery_amount
 * @property int|null $delivery_shop_id
 *
 * @property Shop $deliveryShop
 * @property Order[] $orders
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%delivery}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delivery_name'], 'required'],
            [['delivery_description'], 'string'],
            [['delivery_country', 'delivery_shop_id'], 'integer'],
            [['delivery_name', 'delivery_amount'], 'string', 'max' => 255],
            [['delivery_shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shop::class, 'targetAttribute' => ['delivery_shop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'delivery_name' => Yii::t('app', 'Delivery Name'),
            'delivery_description' => Yii::t('app', 'Delivery Description'),
            'delivery_country' => Yii::t('app', 'Delivery Country'),
            'delivery_amount' => Yii::t('app', 'Delivery Amount'),
            'delivery_shop_id' => Yii::t('app', 'Delivery Shop ID'),
        ];
    }

    /**
     * Gets query for [[DeliveryShop]].
     *
     * @return \yii\db\ActiveQuery|ShopQuery
     */
    public function getDeliveryShop()
    {
        return $this->hasOne(Shop::class, ['id' => 'delivery_shop_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['order_delivery_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return DeliveryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DeliveryQuery(get_called_class());
    }
}
