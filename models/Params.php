<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%params}}".
 *
 * @property int $id
 * @property int $param_product_param_id
 * @property int $param_parameters_id
 * @property float|null $param_value
 * @property int $param_category_id
 * @property float|null $param_low_limit Нижняя граница (-см)
 * @property float|null $param_up_limit Верхняя граница (+см)
 *
 * @property Parameters $paramParameters
 * @property Categories $paramCategory
 * @property ParamUserparam $paramUserparam
 */
class Params extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%params}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'param_parameters_id'], 'required'],
            [['id','param_parameters_id', 'param_category_id'], 'integer'],
            [['param_value', 'param_low_limit', 'param_up_limit'], 'number'],
            [['param_parameters_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parameters::class, 'targetAttribute' => ['param_parameters_id' => 'id']],
            [['param_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['param_category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Param Product Param ID'),
            'param_parameters_id' => Yii::t('app', 'Param Parameters ID'),
            'param_value' => Yii::t('app', 'Param Value'),
            'param_category_id' => Yii::t('app', 'Param Category ID'),
            'param_low_limit' => Yii::t('app', 'Нижняя граница (-см)'),
            'param_up_limit' => Yii::t('app', 'Верхняя граница (+см)'),
        ];
    }

    /**
     * Gets query for [[ParamParameters]].
     *
     * @return \yii\db\ActiveQuery|ParameterQuery
     */
    public function getParamParameters()
    {
        return $this->hasOne(Parameters::class, ['id' => 'param_parameters_id']);
    }

    /**
     * Gets query for [[ParamUserparam]].
     *
     * @return \yii\db\ActiveQuery|ParameterQuery
     */
    public function getParamUserparam()
    {
        return $this->hasMany(ParamUserparam::class, ['param_id' => 'id']);
    }

    /**
     * Gets query for [[ParamCategory]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getParamCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'param_category_id']);
    }
    /**
     * Gets query for [[ParamCategory]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getProductSizes()
    {
        return $this->hasMany(ProductSizes::class, ['ps_param_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ParamsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParamsQuery(get_called_class());
    }
}
