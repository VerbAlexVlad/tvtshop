<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%favorites_products}}".
 *
 * @property int $id
 * @property int $fp_user_id
 * @property int $fp_product_id
 *
 * @property User $fpUser
 * @property Products $fpProduct
 * @property int $countProductInFavoritesProducts
 */
class FavoritesProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%favorites_products}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fp_user_id', 'fp_product_id'], 'required'],
            [['fp_user_id', 'fp_product_id'], 'integer'],
            [['fp_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fp_user_id' => 'id']],
            [['fp_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['fp_product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fp_user_id' => Yii::t('app', 'Fp User ID'),
            'fp_product_id' => Yii::t('app', 'Fp Product ID'),
        ];
    }

    /**
     * Gets query for [[FpUser]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getFpUser()
    {
        return $this->hasOne(User::class, ['id' => 'fp_user_id']);
    }

    /**
     * Gets query for [[FpProduct]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getFpProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'fp_product_id']);
    }

    /**
     * @return int
     */
    public function countProductInFavoritesProducts()
    {
        $count = 0;

        if (!Yii::$app->user->isGuest) {
            $count = self::find()
                ->where(['fp_user_id' => Yii::$app->user->id])
                ->count();
        } else if(isset($_SESSION['favorites_products']) && !empty(isset($_SESSION['favorites_products']))) {
            $count = count($_SESSION['favorites_products']);
        }

        return $count;
    }

    /**
     * @return array|mixed
     */
    public function favoritesProductsList()
    {
        $favoritesProductsList = false;

        if (!Yii::$app->user->isGuest) {
            $favoritesProductsList = FavoritesProducts::find()->select(['fp_product_id', 'id'])
                ->where(['fp_user_id' => Yii::$app->user->id])
                ->indexBy('fp_product_id')
                ->column();
        } else if(isset($_SESSION['favorites_products']) && !empty(isset($_SESSION['favorites_products']))) {
            $favoritesProductsList = $_SESSION['favorites_products'];
        }
        return $favoritesProductsList;
    }
    /**
     * {@inheritdoc}
     * @return FavoritesProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FavoritesProductsQuery(get_called_class());
    }
}
