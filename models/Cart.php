<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cart}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $qty
 * @property int $product_size_id
 *
 * @property User $user
 * @property ProductSizes $productSize
 * @property-read null|bool|string|int $countProductsInCart
 * @property-read array|false|int $productsInCart
 * @property Products $product
 * @property int $dimension_ruler_id [int(11)]
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%cart}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['user_id', 'product_id', 'qty', 'product_size_id'], 'required'],
            [['id', 'user_id', 'product_id', 'qty', 'product_size_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['product_size_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductSizes::class, 'targetAttribute' => ['product_size_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'qty' => Yii::t('app', 'Qty'),
            'product_size_id' => Yii::t('app', 'Product Size ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSize(): \yii\db\ActiveQuery
    {
        return $this->hasOne(ProductSizes::class, ['id' => 'product_size_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * @return bool|int|string|null
     */
    public function getCountProductsInCart()
    {
        $countProductsInCart = 0;

        if (!Yii::$app->user->isGuest) {
            $countProductsInCart = self::find()->where(['user_id' => Yii::$app->user->id])->indexBy('product_size_id')->count();
        } else {
            if (isset($_SESSION['sizes_in_cart']) && !empty($_SESSION['sizes_in_cart'])) {
                $countProductsInCart = count($_SESSION['sizes_in_cart']);
            }
        }
        return $countProductsInCart;
    }

    /**
     * {@inheritdoc}
     * @return CartQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CartQuery(static::class);
    }

    /**
     * @param $product_size_id
     * @param false $sizes_in_cart
     * @return int|mixed
     */
    public function getQtyProductsInCart($product_size_id, $sizes_in_cart = false)
    {
        $result = 1;

        if (!Yii::$app->user->isGuest) {
            if (!empty($sizes_in_cart)) {
                $result = $sizes_in_cart[$product_size_id]->qty;
            } else {
                $product_size_in_cart = self::find()
                    ->where(['user_id' => Yii::$app->user->id])
                    ->andWhere(['product_size_id' => $product_size_id])
                    ->limit(1)
                    ->one();
                if ($product_size_in_cart) {
                    $result = $product_size_in_cart->qty;
                }
            }

        } else if (!empty($sizes_in_cart)) {
            $result = $sizes_in_cart[$product_size_id]['qty'];
        } else {
            $result = $_SESSION['sizes_in_cart'][$product_size_id]['qty'] ?? 1;
        }
        return $result;
    }

    /**
     * @param $product_size_id
     * @return Cart|array|false|mixed|null
     */
    public function getProductInCart($product_size_id)
    {
        $productInCart = false;

        if (!Yii::$app->user->isGuest) {
            $productInCart = self::find()
                ->where([
                    'user_id' => Yii::$app->user->id,
                    'product_size_id' => $product_size_id,
                ])
                ->indexBy('product_size_id')
                ->limit(1)
                ->one();
        } else if (isset($_SESSION['sizes_in_cart'][$product_size_id]) && is_array($_SESSION['sizes_in_cart'][$product_size_id])) {
            $productInCart = $_SESSION['sizes_in_cart'][$product_size_id];
        }

        return $productInCart;
    }

    /**
     * @return Cart[]|array|false
     */
    public function getProductsInCart()
    {
        $productsInCart = false;

        if (!Yii::$app->user->isGuest) {
            $productsInCart = self::find()
                ->where([
                    'user_id' => Yii::$app->user->id,
                ])
                ->with('product')
                ->indexBy('product_size_id')
                ->all();
        } else if (isset($_SESSION['sizes_in_cart']) && !empty($_SESSION['sizes_in_cart']) && is_array($_SESSION['sizes_in_cart'])) {
            $productsInCart = $_SESSION['sizes_in_cart'];
        }

        return $productsInCart;
    }
}
