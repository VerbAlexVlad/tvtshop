<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%param_userparam}}".
 *
 * @property int|null $param_id
 * @property int|null $userparam_id
 */
class ParamUserparam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%param_userparam}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['param_id', 'userparam_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'param_id' => Yii::t('app', 'Param ID'),
            'userparam_id' => Yii::t('app', 'Userparam ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ParamUserparamQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParamUserparamQuery(get_called_class());
    }
}
