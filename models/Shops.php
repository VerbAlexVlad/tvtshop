<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%shops}}".
 *
 * @property int $id
 * @property int|null $shop_owner_user_id
 * @property string $shop_name Имя магазина
 * @property string|null $shop_email
 * @property string $shop_adress адрес
 * @property int|null $shop_sity_id город
 * @property string $shop_work_phone основной телефон
 * @property string $shop_add_phone дополнительный телефон
 * @property string $shop_contact_person контактное лицо
 * @property string|null $shop_postcode почтовый индекс
 * @property string|null $shop_content
 * @property string $shop_alias
 * @property int|null $shop_status
 *
 * @property Delivery[] $deliveries
 * @property FavoritesShop[] $favoritesShops
 * @property OrderItem[] $orderItems
 * @property Product[] $products
 * @property Provider[] $providers
 * @property User $shopOwnerUser
 * @property Sity $shopSity
 * @property User[] $users
 */
class Shops extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shops}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_owner_user_id', 'shop_sity_id', 'shop_status'], 'integer'],
            [['shop_name', 'shop_adress', 'shop_work_phone', 'shop_add_phone', 'shop_contact_person', 'shop_alias'], 'required'],
            [['shop_name', 'shop_content'], 'string'],
            [['shop_email', 'shop_adress', 'shop_contact_person'], 'string', 'max' => 255],
            [['shop_work_phone', 'shop_add_phone'], 'string', 'max' => 20],
            [['shop_postcode'], 'string', 'max' => 7],
            [['shop_alias'], 'string', 'max' => 100],
            [['shop_owner_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['shop_owner_user_id' => 'id']],
            [['shop_sity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sity::class, 'targetAttribute' => ['shop_sity_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'shop_owner_user_id' => Yii::t('app', 'Shop Owner User ID'),
            'shop_name' => Yii::t('app', 'Имя магазина'),
            'shop_email' => Yii::t('app', 'Shop Email'),
            'shop_adress' => Yii::t('app', 'адрес'),
            'shop_sity_id' => Yii::t('app', 'город'),
            'shop_work_phone' => Yii::t('app', 'основной телефон'),
            'shop_add_phone' => Yii::t('app', 'дополнительный телефон'),
            'shop_contact_person' => Yii::t('app', 'контактное лицо'),
            'shop_postcode' => Yii::t('app', 'почтовый индекс'),
            'shop_content' => Yii::t('app', 'Shop Content'),
            'shop_alias' => Yii::t('app', 'Shop Alias'),
            'shop_status' => Yii::t('app', 'Shop Status'),
        ];
    }

    /**
     * Gets query for [[Deliveries]].
     *
     * @return \yii\db\ActiveQuery|DeliveryQuery
     */
    public function getDeliveries()
    {
        return $this->hasMany(Delivery::class, ['delivery_shop_id' => 'id']);
    }


    /**
     * Gets query for [[Deliveries]].
     *
     * @return \yii\db\ActiveQuery|DeliveryQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['payment_shop_id' => 'id']);
    }

    /**
     * Gets query for [[FavoritesShops]].
     *
     * @return \yii\db\ActiveQuery|FavoritesShopQuery
     */
    public function getFavoritesShops()
    {
        return $this->hasMany(FavoritesShop::class, ['fs_shop_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|OrderItemQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['oi_shop_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['product_shop_id' => 'id']);
    }

    /**
     * Gets query for [[Providers]].
     *
     * @return \yii\db\ActiveQuery|ProviderQuery
     */
    public function getProviders()
    {
        return $this->hasMany(Provider::class, ['provider_shop_id' => 'id']);
    }

    /**
     * Gets query for [[ShopOwnerUser]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getShopOwnerUser()
    {
        return $this->hasOne(User::class, ['id' => 'shop_owner_user_id']);
    }

    /**
     * Gets query for [[ShopSity]].
     *
     * @return \yii\db\ActiveQuery|SityQuery
     */
    public function getShopSity()
    {
        return $this->hasOne(Sity::class, ['id' => 'shop_sity_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['shop_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ShopsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopsQuery(get_called_class());
    }
}
