<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%categories_descriptions}}".
 *
 * @property int $id
 * @property string $description Описание
 * @property int $category_id id категории
 * @property int $category_floor_id Для какого пола описание
 * @property string $category_title Title для категории
 *
 * @property Categories $category
 * @property Floor $categoryFloor
 */
class CategoriesDescriptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%categories_descriptions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'category_id', 'category_floor_id', 'category_title'], 'required'],
            [['description'], 'string'],
            [['category_id', 'category_floor_id'], 'integer'],
            [['category_title'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['category_floor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Floor::class, 'targetAttribute' => ['category_floor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'description' => Yii::t('app', 'Описание'),
            'category_id' => Yii::t('app', 'id категории'),
            'category_floor_id' => Yii::t('app', 'Для какого пола описание'),
            'category_title' => Yii::t('app', 'Title для категории'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[CategoryFloor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryFloor()
    {
        return $this->hasOne(Floor::class, ['id' => 'category_floor_id']);
    }
}
