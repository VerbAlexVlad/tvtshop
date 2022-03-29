<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%brands}}".
 *
 * @property int $id
 * @property string $brand_name
 * @property string $brand_alias
 *
 * @property Model[] $models
 */
class Brands extends \yii\db\ActiveRecord
{
    public $count = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%brands}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand_name', 'brand_alias'], 'required'],
            [['brand_name', 'brand_alias'], 'string', 'max' => 50],
            [['count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'brand_name' => Yii::t('app', 'Brand Name'),
            'brand_alias' => Yii::t('app', 'Brand Alias'),
        ];
    }

    /**
     * Gets query for [[Models]].
     *
     * @return \yii\db\ActiveQuery|ModelQuery
     */
    public function getModels()
    {
        return $this->hasMany(Models::class, ['brand_id' => 'id']);
    }

    /**
     * @param $brand_id
     * @return Brands|null
     */
    public function getBrandById($brand_id)
    {
        return self::findOne($brand_id);
    }

    /**
     * {@inheritdoc}
     * @return BrandsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BrandsQuery(get_called_class());
    }
}
