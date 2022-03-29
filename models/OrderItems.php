<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_items}}".
 *
 * @property int $id
 * @property int $oi_order_id
 * @property int $oi_product_size_id
 * @property int $oi_shop_id
 * @property int $oi_status
 * @property float $oi_price
 * @property int $oi_qty_item
 * @property int|null $oi_discount_id скидка
 *
 * @property Orders $oiOrder
 * @property ProductSizes $oiProductSize
 * @property Shops $oiShop
 * @property Discounts $oiDiscount
 */
class OrderItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['oi_order_id', 'oi_product_size_id', 'oi_shop_id', 'oi_price', 'oi_qty_item'], 'required'],
            [['oi_order_id', 'oi_product_size_id', 'oi_shop_id', 'oi_status', 'oi_qty_item', 'oi_discount_id'], 'integer'],
            [['oi_price'], 'number'],
            [['id'], 'unique'],
            [['oi_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['oi_order_id' => 'id']],
            [['oi_product_size_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductSizes::class, 'targetAttribute' => ['oi_product_size_id' => 'id']],
            [['oi_shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shops::class, 'targetAttribute' => ['oi_shop_id' => 'id']],
            [['oi_discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discounts::class, 'targetAttribute' => ['oi_discount_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'oi_order_id' => Yii::t('app', 'Oi Order ID'),
            'oi_product_size_id' => Yii::t('app', 'Oi Product Size ID'),
            'oi_shop_id' => Yii::t('app', 'Oi Shop ID'),
            'oi_status' => Yii::t('app', 'Oi Status'),
            'oi_price' => Yii::t('app', 'Oi Price'),
            'oi_qty_item' => Yii::t('app', 'Oi Qty Item'),
            'oi_discount_id' => Yii::t('app', 'скидка'),
        ];
    }

    /**
     * Gets query for [[OiOrder]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOiOrder()
    {
        return $this->hasOne(Orders::class, ['id' => 'oi_order_id']);
    }

    /**
     * Gets query for [[OiProductSize]].
     *
     * @return \yii\db\ActiveQuery|ProductSizeQuery
     */
    public function getOiProductSize()
    {
        return $this->hasOne(ProductSizes::class, ['id' => 'oi_product_size_id']);
    }

    /**
     * Gets query for [[OiShop]].
     *
     * @return \yii\db\ActiveQuery|ShopQuery
     */
    public function getOiShop()
    {
        return $this->hasOne(Shops::class, ['id' => 'oi_shop_id']);
    }

    /**
     * Gets query for [[OiDiscount]].
     *
     * @return \yii\db\ActiveQuery|DiscountQuery
     */
    public function getOiDiscount()
    {
        return $this->hasOne(Discounts::class, ['id' => 'oi_discount_id']);
    }

    /**
     * {@inheritdoc}
     * @return OrderItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderItemsQuery(get_called_class());
    }
}
