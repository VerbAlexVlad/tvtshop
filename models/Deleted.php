<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%deleted}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $desc
 * @property string|null $url
 * @property int|null $votch
 */
class Deleted extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%deleted}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['votch', 'good', 'bad'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'url' => Yii::t('app', 'Url'),
            'votch' => Yii::t('app', 'Votch'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DeletedQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DeletedQuery(get_called_class());
    }
}
