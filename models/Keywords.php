<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%keywords}}".
 *
 * @property int $id
 * @property string $keyword_value
 *
 * @property ProductsKey[] $productsKeys
 */
class Keywords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%keywords}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keyword_value'], 'required'],
            [['keyword_value'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'keyword_value' => Yii::t('app', 'Keyword Value'),
        ];
    }

    /**
     * Gets query for [[ProductsKeys]].
     *
     * @return \yii\db\ActiveQuery|ProductsKeyQuery
     */
    public function getProductsKeys()
    {
        return $this->hasMany(ProductsKey::class, ['pk_key_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return KeywordsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KeywordsQuery(get_called_class());
    }
}
