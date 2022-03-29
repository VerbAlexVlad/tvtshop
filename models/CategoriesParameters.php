<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%categories_parameters}}".
 *
 * @property int $id
 * @property int $cp_category_id
 * @property int $cp_parameter_id
 * @property float|null $cp_before
 * @property float|null $cp_after
 * @property string|null $cp_parametr_description
 *
 * @property Category $cpCategory
 * @property Parameter $cpParameter
 */
class CategoriesParameters extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%categories_parameters}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cp_category_id', 'cp_parameter_id'], 'required'],
            [['cp_category_id', 'cp_parameter_id'], 'integer'],
            [['cp_before', 'cp_after'], 'number'],
            [['cp_parametr_description'], 'string', 'max' => 255],
            [['cp_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['cp_category_id' => 'id']],
            [['cp_parameter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parameters::class, 'targetAttribute' => ['cp_parameter_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cp_category_id' => Yii::t('app', 'Cp Category ID'),
            'cp_parameter_id' => Yii::t('app', 'Cp Parameter ID'),
            'cp_before' => Yii::t('app', 'Cp Before'),
            'cp_after' => Yii::t('app', 'Cp After'),
            'cp_parametr_description' => Yii::t('app', 'Cp Parametr Description'),
        ];
    }

    /**
     * Gets query for [[CpCategory]].
     *
     * @return \yii\db\ActiveQuery|CategoryQuery
     */
    public function getCpCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'cp_category_id']);
    }

    /**
     * Gets query for [[CpParameter]].
     *
     * @return \yii\db\ActiveQuery|ParameterQuery
     */
    public function getCpParameter()
    {
        return $this->hasOne(Parameters::class, ['id' => 'cp_parameter_id']);
    }

    /**
     * {@inheritdoc}
     * @return CategoriesParametersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoriesParametersQuery(get_called_class());
    }
}
