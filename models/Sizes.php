<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sizes}}".
 *
 * @property int $id
 * @property string $size_name
 * @property string $size_for
 *
 * @property ProductSize[] $productSizes
 */
class Sizes extends \yii\db\ActiveRecord
{
    public $count = 0;
    public $status;
    public $sizeForMe = false;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sizes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size_name', 'size_for'], 'required'],
            [['size_name', 'size_for'], 'string', 'max' => 20],
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
            'size_name' => Yii::t('app', 'Size Name'),
            'size_for' => Yii::t('app', 'Size For'),
        ];
    }

    /**
     * Gets query for [[ProductSizes]].
     *
     * @return \yii\db\ActiveQuery|ProductSizeQuery
     */
    public function getProductSizes()
    {
        return $this->hasMany(ProductSize::class, ['ps_size_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return SizesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SizesQuery(get_called_class());
    }
}
