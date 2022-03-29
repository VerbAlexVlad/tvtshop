<?php

namespace app\models;

use phpDocumentor\Reflection\Types\Self_;
use Yii;

/**
 * This is the model class for table "{{%parameters}}".
 *
 * @property int $id
 * @property string $parameter_name
 * @property int $parameter_type
 *
 * @property CategoriesParameter[] $categoriesParameters
 * @property Param[] $params
 * @property ProductSizeParam[] $productSizeParams
 * @property Userparam[] $userparams
 */
class Parameters extends \yii\db\ActiveRecord
{
    /**
     * @return array|\string[][]
     */
    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'app\behaviors\ImageBehave',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%parameters}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parameter_name'], 'required'],
            [['parameter_type'], 'integer'],
            [['parameter_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parameter_name' => Yii::t('app', 'Parameter Name'),
            'parameter_type' => Yii::t('app', 'Parameter Type'),
        ];
    }

    /**
     * Gets query for [[CategoriesParameters]].
     *
     * @return \yii\db\ActiveQuery|CategoriesParameterQuery
     */
    public function getCategoriesParameters()
    {
        return $this->hasMany(CategoriesParameter::class, ['cp_parameter_id' => 'id']);
    }

    /**
     * Gets query for [[Params]].
     *
     * @return \yii\db\ActiveQuery|ParamQuery
     */
    public function getParams()
    {
        return $this->hasMany(Param::class, ['param_parameters_id' => 'id']);
    }

    /**
     * Gets query for [[ProductSizeParams]].
     *
     * @return \yii\db\ActiveQuery|ProductSizeParamQuery
     */
    public function getProductSizeParams()
    {
        return $this->hasMany(ProductSizeParam::class, ['parameter_id' => 'id']);
    }

    /**
     * Gets query for [[Userparams]].
     *
     * @return \yii\db\ActiveQuery|UserparamQuery
     */
    public function getUserparams()
    {
        return $this->hasMany(Userparam::class, ['userparam_parameters_id' => 'id']);
    }
    /**
     * Gets query for [[Userparams]].
     *
     * @return \yii\db\ActiveQuery|UserparamQuery
     */
    public function getAllMainParameters()
    {
        return self::find()->where(['parameter_type' => 1])->all();
    }

    /**
     * {@inheritdoc}
     * @return ParametersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParametersQuery(get_called_class());
    }
}
