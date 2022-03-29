<?php


namespace app\models;
use yii\base\Model;
use Yii;

class UserRegistration extends Model
{
    public $email;
    public $username;
    public $surname;
    public $patronymic;
    public $phone;
    public $login;
    public $adress;
    public $region;
    public $postcode;
    public $sity_name;
    public $status;
    public $floor;
    public $password_hash;
    public $auth_key;
    public $rememberMe = true;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                [
                    'username',
                    'surname',
                    'patronymic',
                    'login',
                    'email',
                    'floor',
                    'phone',
                    'region',
                    'postcode',
                    'sity_name',
                    'adress',
                    'password_hash',
                ], 'filter', 'filter' => 'trim'
            ],
            [
                [
                    'email',
                    'password_hash',
                ], 'required'
            ],
            [
                ['password_hash'], 'required', 'on' => 'create'
            ],
            [
                ['adress'], 'string'
            ],
            [
                ['floor'], 'integer'
            ],
            [
                [
                    'username',
                    'patronymic',
                    'phone',
                    'adress',
                    'password_hash',
                    'auth_key'
                ], 'string', 'max' => 255
            ],
            ['postcode', 'string', 'max' => 10],
            [
                ['surname', 'login'], 'string', 'max' => 20
            ],
            [
                'email', 'unique',
                'targetClass' => User::class,
                'message'     => 'Пользователь с данным электронным адресом уже зарегистрирован, авторизуйтесь используя его, введите другой или воспользуйтесь функцией востановления пароля.'
            ],
            [
                'login', 'unique',
                'targetClass' => User::class,
                'message'     => 'Данный логин уже занят, введите другой.'
            ],


            [
                'status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'
            ],
            [
                'status', 'in', 'range' => [
                User::STATUS_NOT_ACTIVE,
                User::STATUS_ACTIVE,
            ]
            ],
            [
                'status', 'default', 'value' => User::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'
            ],
            [
                'email', 'email'
            ],
            [
                'rememberMe', 'boolean'
            ],

        ];
    }

    public function attributeLabels()
    {
        return [
            // username and password are both required
            'email'         => \Yii::t('app', 'Укажите свой email'),
            'region'        => \Yii::t('app', 'Укажите регион'),
            'sity_name'     => \Yii::t('app', 'Укажите Город'),
            'postcode'      => \Yii::t('app', 'Укажите Индекс'),
            'username'      => \Yii::t('app', 'Укажите своё Имя'),
            'patronymic'    => \Yii::t('app', 'Укажите своё Отчество'),
            'surname'       => \Yii::t('app', 'Укажите свою Фамилию'),
            'floor'         => \Yii::t('app', 'Укажите свой пол'),
            'phone'         => \Yii::t('app', 'Укажите свой сотовый телефон'),
            'adress'        => \Yii::t('app', 'Укажите свой адрес проживания'),
            'rememberMe'    => \Yii::t('app', 'Запомнить меня'),
            'password_hash' => \Yii::t('app', 'Укажите Пароль')
        ];
    }

    public function reg()
    {
        $user = User::reg(
            $this->email,
            $this->password_hash,
            $this->status
        );

        if ($this->scenario === 'emailActivation') $user->generateSecretKey();

        $user->save();
        return $user->save() ? $user : null;
    }

    public function sendActivationEmail($user)
    {
        return Yii::$app->mailer->compose('activationEmail', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' (отправлено роботом)'])
//             ->setFrom(['verbuilder@yandex.ru' => 'Качественные покупки'])
            ->setTo($this->email ?: $user->email)
            ->setSubject('Активация для ' . Yii::$app->name)
            ->send();
    }


    public function sendActivationEmailAndPassword($user, $password)
    {
        return Yii::$app->mailer->compose('activationEmail', ['user' => $user, 'password' => $password])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' (отправлено роботом)'])
            ->setTo($user->email)
            ->setSubject('Активация для ' . Yii::$app->name)
            ->send();
    }
}