<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%models}}".
 *
 * @property int $id
 * @property string $model_name
 * @property int $category_id
 * @property int $brand_id
 *
 * @property Categories $category
 * @property Brands $brand
 * @property Products[] $products
 */
class Models extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%models}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_name', 'category_id', 'brand_id'], 'required'],
            [['category_id', 'brand_id'], 'integer'],
            [['model_name'], 'string', 'max' => 250],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brands::class, 'targetAttribute' => ['brand_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_name' => Yii::t('app', 'Model Name'),
            'category_id' => Yii::t('app', 'Category'),
            'brand_id' => Yii::t('app', 'Brand'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brands::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getIds()
    {
        return $this->hasMany(CategoriesParameters::className(), ['cp_category_id' => 'id'])->viaTable('{{%categories}}', ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['product_model_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ModelsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ModelsQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return "{$this->category->name}. {$this->brand->brand_name}. {$this->model_name}";
    }
}
