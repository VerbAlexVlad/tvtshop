<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.08.2015
 * Time: 15:21
 */

namespace app\models;

use Yii;
use yii\base\Model;

class SendemailForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => [
                    'status' => User::STATUS_ACTIVE
                ],
                'message' => 'Данный емайл не зарегистрирован.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Для восстановления введите свой email'
        ];
    }

    /**
     * @return bool
     */
    public function sendemail()
    {
        if ($user = (new User())::findOne(
            [
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email
            ]
        )):
            $user->generateSecretKey();

            if ($user->save()):
                return \Yii::$app->mailer->compose(
                    [
                        'html' => 'views/sendemail/html',
                        'text' => 'views/sendemail/text',
                    ],
                    ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' (отправлено роботом)'])
                    ->setTo($this->email)
                    ->setSubject('Сброс пароля для ' . Yii::$app->name)
                    ->send();
            endif;
        endif;
        return false;
    }
}