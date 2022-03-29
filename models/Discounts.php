<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%discounts}}".
 *
 * @property int $id
 * @property string|null $discount_name
 * @property float $discount_value
 * @property int $discount_unit_id
 * @property string $discount_from_date
 * @property string $discount_to_date
 *
 * @property DiscountUnits $discountUnit
 * @property OrderItems[] $orderItems
 * @property ProductSizes[] $productSizes
 * @property-read string $discountValue
 * @property Products[] $products
 */
class Discounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%discounts}}';
    }

    /**
     * @return DiscountsQuery
     */
    public static function find()
    {
        return new DiscountsQuery(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'discount_value', 'discount_unit_id', 'discount_from_date', 'discount_to_date'], 'required'],
            [['id', 'discount_unit_id'], 'integer'],
            [['discount_value'], 'number'],
            [['discount_from_date', 'discount_to_date'], 'safe'],
            [['discount_name'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['discount_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => DiscountUnits::class, 'targetAttribute' => ['discount_unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'discount_name' => Yii::t('app', 'Discount Name'),
            'discount_value' => Yii::t('app', 'Discount Value'),
            'discount_unit_id' => Yii::t('app', 'Discount Unit ID'),
            'discount_from_date' => Yii::t('app', 'Discount From Date'),
            'discount_to_date' => Yii::t('app', 'Discount To Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountUnit()
    {
        return $this->hasOne(DiscountUnits::class, ['id' => 'discount_unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['oi_discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSizes()
    {
        return $this->hasMany(ProductSizes::class, ['ps_discount_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['product_discount' => 'id']);
    }

    /**
     * @return string
     */
    public function getDiscountValue()
    {
        return Html::tag('span', "{$this->discount_value} {$this->discountUnit->discount_units_name}", ['class' => 'discount-value_product-item']);
    }
}
