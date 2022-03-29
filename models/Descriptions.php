<?php

namespace app\models;

use Yii;

/**
 * Class Descriptions
 * @package app\models
 * @property int $id [int(11)]
 * @property int $description_product_id [int(11)]
 * @property string $description_text
 */
class Descriptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%descriptions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description_text'], 'required'],
            [['description_text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'description_text' => Yii::t('app', 'Description Text'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DescriptionsQuery the active query used by this AR class.
     */
    public static function find(): DescriptionsQuery
    {
        return new DescriptionsQuery(static::class);
    }
}
