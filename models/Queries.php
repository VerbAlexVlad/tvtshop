<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%queries}}".
 *
 * @property int $id
 * @property string $queries_text
 * @property int $queries_count
 */
class Queries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%queries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['queries_text'], 'required'],
            [['queries_count'], 'integer'],
            [['queries_text'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'queries_text' => 'Queries Text',
            'queries_count' => 'Queries Count',
        ];
    }
}
