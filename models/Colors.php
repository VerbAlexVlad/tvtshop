<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%colors}}".
 *
 * @property int $id
 * @property string $color_name
 * @property string $color_html
 *
 * @property ProductColor[] $productColors
 */
class Colors extends \yii\db\ActiveRecord
{
    public $count = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%colors}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color_name', 'color_html'], 'required'],
            [['color_name'], 'string', 'max' => 50],
            [['color_html'], 'string', 'max' => 10],
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
            'color_name' => Yii::t('app', 'Color Name'),
            'color_html' => Yii::t('app', 'Color Html'),
        ];
    }

    /**
     * Gets query for [[ProductColors]].
     *
     * @return \yii\db\ActiveQuery|ProductColorQuery
     */
    public function getProductColors()
    {
        return $this->hasMany(ProductColors::class, ['color_id' => 'id']);
    }

    /**
     * @param $color_id
     * @return Colors|null
     */
    public function getColorById($color_id)
    {
        return self::findOne($color_id);
    }

    /**
     * {@inheritdoc}
     * @return ColorsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ColorsQuery(get_called_class());
    }
}
