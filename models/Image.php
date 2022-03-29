<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property int $id
 * @property string $filePath
 * @property int|null $itemId
 * @property int|null $isMain
 * @property string $modelName
 * @property string $urlAlias
 * @property string|null $name
 *
 * @property Products $product
 * @property string $gallery_id [varchar(50)]
 * @property int $sort [int(11)]
 * @property string $title [varchar(255)]
 * @property string $alt [varchar(255)]
 * @property string $description
 */
class Image extends \app\base\Image
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filePath', 'modelName', 'urlAlias'], 'required'],
            [['itemId', 'isMain'], 'integer'],
            [['filePath', 'urlAlias'], 'string', 'max' => 400],
            [['modelName'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'filePath' => Yii::t('app', 'File Path'),
            'itemId' => Yii::t('app', 'Item ID'),
            'isMain' => Yii::t('app', 'Is Main'),
            'modelName' => Yii::t('app', 'Model Name'),
            'urlAlias' => Yii::t('app', 'Url Alias'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'itemId']);
    }
//
//    /**
//     * {@inheritdoc}
//     * @return ImageQuery the active query used by this AR class.
//     */
//    public static function find()
//    {
//        return new ImageQuery(get_called_class());
//    }
}
