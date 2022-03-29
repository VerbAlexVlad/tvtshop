<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%providers}}".
 *
 * @property int $id
 * @property string $provider_company_name Наименование компании
 * @property string $provider_contact_name Контактное лицо
 * @property string $provider_contact_phone Контактный телефон
 * @property int $provider_shop_id id Магазина, добавившего поставщика
 *
 * @property Shop $providerShop
 * @property Receipt[] $receipts
 */
class Providers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%providers}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'provider_company_name', 'provider_contact_name', 'provider_contact_phone', 'provider_shop_id'], 'required'],
            [['id', 'provider_shop_id'], 'integer'],
            [['provider_company_name'], 'string', 'max' => 100],
            [['provider_contact_name'], 'string', 'max' => 50],
            [['provider_contact_phone'], 'string', 'max' => 20],
            [['id'], 'unique'],
            [['provider_shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shop::class, 'targetAttribute' => ['provider_shop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'provider_company_name' => Yii::t('app', 'Наименование компании'),
            'provider_contact_name' => Yii::t('app', 'Контактное лицо'),
            'provider_contact_phone' => Yii::t('app', 'Контактный телефон'),
            'provider_shop_id' => Yii::t('app', 'id Магазина, добавившего поставщика'),
        ];
    }

    /**
     * Gets query for [[ProviderShop]].
     *
     * @return \yii\db\ActiveQuery|ShopQuery
     */
    public function getProviderShop()
    {
        return $this->hasOne(Shop::class, ['id' => 'provider_shop_id']);
    }

    /**
     * Gets query for [[Receipts]].
     *
     * @return \yii\db\ActiveQuery|ReceiptQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(Receipt::class, ['receipt_provider_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProvidersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProvidersQuery(get_called_class());
    }
}
