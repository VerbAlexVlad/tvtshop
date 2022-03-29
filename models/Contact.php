<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%contact}}".
 *
 * @property int $id
 * @property string $contact_username
 * @property string $contact_phone
 * @property string $contact_text
 * @property string $contact_data_create
 * @property string|null $contact_social_networks
 * @property int|null $contact_type 0-покупатель, 1-продавец
 * @property string|null $contact_shop_name
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contact_username', 'contact_phone', 'contact_text'], 'required'],
            [['contact_data_create'], 'safe'],
            [['contact_type'], 'integer'],
            [['contact_username'], 'string', 'max' => 80],
            [['contact_phone'], 'string', 'max' => 20],
            [['contact_text', 'contact_social_networks', 'contact_shop_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'contact_username' => Yii::t('app', 'Contact Username'),
            'contact_phone' => Yii::t('app', 'Contact Phone'),
            'contact_text' => Yii::t('app', 'Contact Text'),
            'contact_data_create' => Yii::t('app', 'Contact Data Create'),
            'contact_social_networks' => Yii::t('app', 'Contact Social Networks'),
            'contact_type' => Yii::t('app', '0-покупатель, 1-продавец'),
            'contact_shop_name' => Yii::t('app', 'Contact Shop Name'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ContactQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ContactQuery(get_called_class());
    }
}
