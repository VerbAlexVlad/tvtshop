<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%characteristics}}".
 *
 * @property int $id
 * @property string $characteristic_name
 * @property string $characteristic_placeholder
 *
 * @property CategoriesCharacteristics[] $categoriesCharacteristics
 * @property ProductsCharacteristics[] $productsCharacteristics
 */
class Characteristics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%characteristics}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['characteristic_name', 'characteristic_placeholder'], 'required'],
            [['characteristic_name', 'characteristic_placeholder'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'characteristic_name' => Yii::t('app', 'Characteristic Name'),
            'characteristic_placeholder' => Yii::t('app', 'Characteristic Placeholder'),
        ];
    }

    /**
     * Gets query for [[CategoriesCharacteristics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesCharacteristics()
    {
        return $this->hasMany(CategoriesCharacteristics::class, ['ch_characteristic_id' => 'id']);
    }

    /**
     * Gets query for [[ProductsCharacteristics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductsCharacteristics()
    {
        return $this->hasMany(ProductsCharacteristics::class, ['ph_characteristic_id' => 'id']);
    }
}
