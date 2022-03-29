<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%userparams}}".
 *
 * @property int $id
 * @property int $userparam_parameters_id
 * @property float $userparam_value
 * @property int $userparam_param_num
 *
 * @property Parameter $userparamParameters
 */
class Userparams extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%userparams}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userparam_parameters_id', 'userparam_value', 'userparam_param_num'], 'required'],
            [['userparam_parameters_id', 'userparam_param_num'], 'integer'],
            [['userparam_value'], 'number'],
            [['userparam_parameters_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parameters::class, 'targetAttribute' => ['userparam_parameters_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userparam_parameters_id' => Yii::t('app', 'Userparam Parameters ID'),
            'userparam_value' => Yii::t('app', 'Userparam Value'),
            'userparam_param_num' => Yii::t('app', 'Userparam Param Num'),
        ];
    }

    /**
     * Gets query for [[UserparamParameters]].
     *
     * @return \yii\db\ActiveQuery|ParameterQuery
     */
    public function getUserparamParameters()
    {
        return $this->hasOne(Parameters::class, ['id' => 'userparam_parameters_id']);
    }
    /**
     * Gets query for [[UserparamParameters]].
     *
     * @return \yii\db\ActiveQuery|ParameterQuery
     */
    public function setUserParamId($userparam_param_num)
    {
        if (!Yii::$app->user->isGuest) {

        } else {
            $_SESSION['user']['param'] = $userparam_param_num;
        }
    }
    /**
     * Gets query for [[UserparamParameters]].
     *
     * @return \yii\db\ActiveQuery|ParameterQuery
     */
    public function getUserParamId()
    {
        if (!Yii::$app->user->isGuest) {
            $userParam_id = Yii::$app->user->identity->param ?? null;
        } else {
            $userParam_id = $_SESSION['user']['param'] ?? null;
        }

        return $userParam_id;
    }

    /**
     * {@inheritdoc}
     * @return UserparamsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserparamsQuery(get_called_class());
    }
}
