<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%categories_characteristics}}".
 *
 * @property int $id
 * @property int $ch_category_id Идентификатор категории
 * @property int $ch_characteristic_id
 *
 * @property Category $chCategory
 * @property Characteristic $chCharacteristic
 */
class CategoriesCharacteristics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%categories_characteristics}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ch_category_id', 'ch_characteristic_id'], 'required'],
            [['ch_category_id', 'ch_characteristic_id'], 'integer'],
            [['ch_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['ch_category_id' => 'id']],
            [['ch_characteristic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Characteristic::class, 'targetAttribute' => ['ch_characteristic_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ch_category_id' => Yii::t('app', 'Идентификатор категории'),
            'ch_characteristic_id' => Yii::t('app', 'Ch Characteristic ID'),
        ];
    }

    /**
     * Gets query for [[ChCategory]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getChCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'ch_category_id']);
    }

    /**
     * Gets query for [[ChCharacteristic]].
     *
     * @return \yii\db\ActiveQuery|CharacteristicQuery
     */
    public function getChCharacteristic()
    {
        return $this->hasOne(Characteristics::class, ['id' => 'ch_characteristic_id']);
    }

    /**
     * {@inheritdoc}
     * @return CategoriesCharacteristicsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoriesCharacteristicsQuery(get_called_class());
    }
}
