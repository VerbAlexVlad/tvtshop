<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%seasons}}".
 *
 * @property int $id
 * @property string $season_name
 */
class Seasons extends \yii\db\ActiveRecord
{
    public $count = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%seasons}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['season_name'], 'required'],
            [['season_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'season_name' => Yii::t('app', 'Season Name'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SeasonsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeasonsQuery(get_called_class());
    }
}
