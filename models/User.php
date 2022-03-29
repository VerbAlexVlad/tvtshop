<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $email
 * @property string $surname
 * @property string $username
 * @property string $patronymic
 * @property int $floor  Пол
 * @property string $phone
 * @property int $param_id Если указаны все размеры true, если не все false
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $postcode
 * @property int $created_at
 * @property int $updated_at
 * @property string $secret_key
 * @property string $logged_at
 * @property int $shop_id
 * @property int $status
 * @property Cart[] $carts
 * @property FavoritesProducts[] $favoritesProducts
 * @property FavoritesShops[] $favoritesShops
 * @property Orders[] $orders
 * @property Shops[] $shops
 * @property Shops $shop
 * @property int $confirmed_at [int(11)]
 * @property string $unconfirmed_email [varchar(255)]
 * @property int $blocked_at [int(11)]
 * @property string $registration_ip [varchar(45)]
 * @property int $flags [int(11)]
 * @property int $last_login_at [int(11)]
 */
class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREATE_USER_IN_ORDER = 'create_user_in_order';
    const SCENARIO_CREATE_USER_IN_ONE_CLICK = 'create_user_in_one_click';

    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * @param string $key
     * @return User|null
     */
    public static function findBySecretKey(string $key)
    {
        if (!static::isSecretKeyExpire($key)) {
            return null;
        }
        return static::findOne(['secret_key' => $key]);
    }

    public static function isSecretKeyExpire($key): bool
    {
        if (empty($key)) // если переменная key пустая
        {
            return false;
        }
        $expire = Yii::$app->params['secretKeyExpire']; // переменная expire равна сроку действия секретного ключа, параметр берется из params.php
        $parts = explode('_', $key); // разбиваем строку на массив (разделитель - знак подчеркивания), где первый элемент будет сгенерированный ранее ключ, второй (последний) элемент будет временнем создания ключа.
        $timestamp = (int)end($parts); // помещаем в переменную timestamp последний элемент массива parts, т.е. время создания ключа
        return $timestamp + $expire >= time(); // складываем время создания ключа и время действия ключа, и если полученное значение больше, либо равно текущему времени, возращаем true, иначе, срок действия ключа истек и возвращаем false
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone]);
    }

    /**
     * Находит пользователя по емайл
     * @param $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['email', 'auth_key'], 'required'],
//            [
//                ['order_username', 'order_email', 'order_adress', 'order_sity_id', 'order_address_street'],
//                'required', 'on' => self::SCENARIO_CREATE_USER_IN_ORDER
//            ],
            [
                ['username', 'phone'],
                'required', 'on' => self::SCENARIO_CREATE_USER_IN_ONE_CLICK
            ],
            [['floor', 'param_id', 'created_at', 'updated_at', 'shop_id', 'status'], 'integer'],
            [['logged_at'], 'safe'],
            ['email', 'email'],
            [['email', 'password_hash', 'password_reset_token', 'secret_key','address_locality'], 'string', 'max' => 255],
            [['surname', 'username', 'patronymic'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
//            [['order_address_apartment_number', 'address_house_number'], 'string', 'max' => 15],
            [['auth_key'], 'string', 'max' => 35],
//            [['postcode'], 'string', 'max' => 10],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shops::class, 'targetAttribute' => ['shop_id' => 'id']],
            ['email', 'unique', 'message' => 'Пользователь с данной почтой уже зарегистрирован.'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'param_id' => \Yii::t('app', 'Размер в таблице param'),
            'username' => \Yii::t('app', 'Имя'),
            'patronymic' => \Yii::t('app', 'Отчество'),
            'surname' => \Yii::t('app', 'Фамилия'),
            'email' => \Yii::t('app', 'Email'),
            'address_locality' => \Yii::t('app', 'Адрес проживания'),
            'region' => \Yii::t('app', 'Регион проживания'),
            'password_hash' => \Yii::t('app', 'Password Hash'),
            'phone' => \Yii::t('app', 'Телефон'),
            'sity_name' => \Yii::t('app', 'Город проживания'),
            'status' => \Yii::t('app', 'Статус'),
            'postcode' => \Yii::t('app', 'Индекс почтового отделения'),
            'floor' => \Yii::t('app', 'Пол'),
            'auth_key' => \Yii::t('app', 'Auth Key'),
            'created_at' => \Yii::t('app', 'Дата создания'),
            'updated_at' => \Yii::t('app', 'Дата изменения'),
            'fio' => \Yii::t('app', 'ФИО')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritesProducts()
    {
        return $this->hasMany(FavoritesProducts::class, ['fp_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritesShops()
    {
        return $this->hasMany(FavoritesShops::class, ['fs_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['order_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShops()
    {
        return $this->hasMany(Shops::class, ['shop_owner_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shops::class, ['id' => 'shop_id']);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string|null $password
     * @throws \yii\base\Exception
     */
    public function setPassword(string $password = null)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param $view
     * @return bool
     */
    public function sendActivationEmail($view): bool
    {
        // Set layout params
        Yii::$app->mailer->getView()->params['userName'] = $this->username;

        $result = \Yii::$app->mailer->compose(
            [
                'html' => 'views/' . $view . '/html',
                'text' => 'views/' . $view . '/text',
            ],
            ['user' => $this])
            ->setTo([$this->email => $this->username])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ". Магазин одежды"])
            ->setSubject('Активация для ' . Yii::$app->name)
            ->send();

        // Reset layout params
        \Yii::$app->mailer->getView()->params['userName'] = null;

        return $result;
    }

    /**
     * @param $view
     * @param $password
     * @return bool
     */
    public function sendActivationEmailAndPassword($view, $password): bool
    {
        // Set layout params
        Yii::$app->mailer->getView()->params['userName'] = $this->username;

        $result = \Yii::$app->mailer->compose(
            [
                'html' => 'views/' . $view . '/html',
                'text' => 'views/' . $view . '/text',
            ],
            ['user' => $this, 'password' => $password])
            ->setTo([$this->email => $this->username])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ". Магазин одежды"])
            ->setSubject('Активация для ' . Yii::$app->name)
            ->send();

        // Reset layout params
        \Yii::$app->mailer->getView()->params['userName'] = null;

        return $result;
    }

    /**
     * @param $view
     * @param $order
     * @param $order_items
     * @return bool
     */
    public function sendOrderInfoMessage($view, $order, $order_items): bool
    {
        // Отправка письма заказчику
        Yii::$app->mailer->getView()->params['userName'] = $this->username;

        $result = \Yii::$app->mailer->compose(
            [
                'html' => 'views/' . $view . '/html',
                'text' => 'views/' . $view . '/text',
            ],
            ['user' => $this, 'order' => $order, 'order_items' => $order_items])
            ->setTo([$this->email => $this->username])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ". Магазин одежды"])
            ->setSubject('Информация по заказу №' . $order->id)
            ->send();

        // Reset layout params
        \Yii::$app->mailer->getView()->params['userName'] = null;

        return $result;
    }

    /* Хелперы */
    public function generateSecretKey()
    {
        $this->secret_key = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removeSecretKey()
    {
        $this->secret_key = null;
    }


}