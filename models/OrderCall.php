<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%order_call}}".
 *
 * @property int $id
 * @property string $oc_user_name
 * @property string $oc_user_phone
 * @property string $oc_question
 */
class OrderCall extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_call}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['oc_user_name', 'oc_user_phone'], 'required'],
            [['oc_question'], 'string'],
            [['status'], 'integer'],
            [['oc_user_name', 'oc_user_phone'], 'string', 'max' => 50],
            [['this_url'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'oc_user_name' => Yii::t('app', 'Ваше имя'),
            'oc_user_phone' => Yii::t('app', 'Ваш телефон'),
            'oc_question' => Yii::t('app', 'Коментарий'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OrderCallQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderCallQuery(get_called_class());
    }

    /**
     * @param $view
     * @return bool
     */
    public function sendOrderCall($view)
    {
        $result = \Yii::$app->mailer->compose(
            [
                'html' => 'views/' . $view . '/html',
                'text' => 'views/' . $view . '/text',
            ],
            ['orderCallModel' => $this])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ". Магазин одежды"])
            ->setSubject('Запрос звонка от покупателя.')
            ->send();

        return $result;
    }
}
