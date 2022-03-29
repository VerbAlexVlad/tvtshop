<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_sizes}}".
 *
 * @property int $id
 * @property int $ps_product_id
 * @property int $ps_size_id
 * @property int $ps_param_id
 * @property int $ps_status
 * @property int|null $ps_discount_id
 * @property string|null $ps_internal_id
 *
 * @property Cart[] $carts
 * @property OrderItems[] $orderItems
 * @property Products $psProduct
 * @property Sizes $psSize
 * @property Discounts $psDiscount
 * @property Params[] $params
 * @property Receipts[] $receipts
 */
class ProductSizes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_sizes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ps_product_id', 'ps_size_id', 'ps_param_id'], 'required'],
            [['id', 'ps_product_id', 'ps_size_id', 'ps_param_id', 'ps_status', 'ps_discount_id', 'sizes'], 'integer'],
            [['ps_internal_id'], 'string', 'max' => 50],
            [['ps_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['ps_product_id' => 'id']],
            [['ps_size_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sizes::class, 'targetAttribute' => ['ps_size_id' => 'id']],
            [['ps_discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discounts::class, 'targetAttribute' => ['ps_discount_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ps_product_id' => Yii::t('app', 'Ps Product ID'),
            'ps_size_id' => Yii::t('app', 'Ps Size ID'),
            'ps_param_id' => Yii::t('app', 'Ps Param ID'),
            'ps_status' => Yii::t('app', 'Ps Status'),
            'ps_discount_id' => Yii::t('app', 'Ps Discount ID'),
            'ps_internal_id' => Yii::t('app', 'Ps Internal ID'),
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery|CartQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['product_size_id' => 'id']);
    }
    /**
     * Gets query for [[Params]].
     *
     * @return \yii\db\ActiveQuery|CartQuery
     */
    public function getParams()
    {
        return $this->hasMany(Params::class, ['id' => 'ps_param_id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['oi_product_size_id' => 'id']);
    }

    /**
     * Gets query for [[PsProduct]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getPsProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'ps_product_id']);
    }

    /**
     * Gets query for [[PsSize]].
     *
     * @return \yii\db\ActiveQuery|SizeQuery
     */
    public function getPsSize()
    {
        return $this->hasOne(Sizes::class, ['id' => 'ps_size_id']);
    }

    /**
     * Gets query for [[PsDiscount]].
     *
     * @return \yii\db\ActiveQuery|DiscountQuery
     */
    public function getPsDiscount()
    {
        return $this->hasOne(Discounts::class, ['id' => 'ps_discount_id']);
    }

    /**
     * Gets query for [[Receipts]].
     *
     * @return \yii\db\ActiveQuery|ReceiptQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(Receipts::class, ['receipt_product_size_id' => 'id']);
    }

    public function getArrayProductSizes($model): array
    {
        $returnArray = [];

        foreach ($model as $id=>$item) {
            $returnArray[$id] = $item->psSize;
            $returnArray[$id]->status = $item->ps_status;
            if((new Userparams())->getUserParamId() && !empty($item->params)) {
                $returnArray[$id]->sizeForMe = true;
            }
        }

        usort($returnArray, function($a,$b){
            return strcmp($a["size_name"], $b["size_name"]);
        });

        return $returnArray;
    }

    /**
     * {@inheritdoc}
     * @return ProductSizesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductSizesQuery(get_called_class());
    }
}
