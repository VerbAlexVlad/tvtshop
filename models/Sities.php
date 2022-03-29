<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sities}}".
 *
 * @property int $id
 * @property string $sity_name
 * @property string $rgn
 * @property string $okr
 *
 * @property Order[] $orders
 * @property Shop[] $shops
 * @property User[] $users
 */
class Sities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sities}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sity_name', 'rgn', 'okr'], 'required'],
            [['sity_name', 'rgn', 'okr'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sity_name' => Yii::t('app', 'Sity Name'),
            'rgn' => Yii::t('app', 'Rgn'),
            'okr' => Yii::t('app', 'Okr'),
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['order_sity_id' => 'id']);
    }

    /**
     * Gets query for [[Shops]].
     *
     * @return \yii\db\ActiveQuery|ShopQuery
     */
    public function getShops()
    {
        return $this->hasMany(Shop::class, ['shop_sity_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['sity_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return SitiesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SitiesQuery(get_called_class());
    }
}
