<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property int|null $order_user_id
 * @property string|null $order_username
 * @property string|null $order_patronymic
 * @property string|null $order_surname
 * @property string|null $order_email
 * @property string $order_phone
 * @property int|null $order_sity_id
 * @property string|null $order_adress
 * @property string|null $order_postcode
 * @property string $order_date_modification
 * @property int $order_status
 * @property string|null $order_comment
 * @property string|null $order_user_ip
 * @property float|null $order_price_delivery
 * @property int|null $order_delivery_id
 * @property float|null $order_prepayment
 * @property string $order_created_at
 * @property string|null $order_updated_at
 *
 * @property OrderItems[] $orderItems
 * @property User $orderUser
 * @property Delivery $orderDelivery
 */
class Orders extends ActiveRecord
{
    const SCENARIO_CREATE_ORDER = 'create_order';
    const SCENARIO_CREATE_ORDER_IN_ONE_CLICK = 'create_order_in_one_click';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrdersQuery(get_called_class());
    }

    /**
     * Метод расширяет возможности класса Order, внедряя дополительные
     * свойства и методы. Кроме того, позволяет реагировать на события,
     * создаваемые классом Order или его родителями
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    // при вставке новой записи присвоить атрибутам created
                    // и updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_INSERT => ['order_created_at', 'order_updated_at'],
                    // при обновлении существующей записи  присвоить атрибуту
                    // updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['order_updated_at'],
                ],
                // если вместо метки времени UNIX используется DATETIME
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
//            [['order_phone', 'order_username', 'order_patronymic', 'order_email', 'order_surname', 'order_address_locality', 'order_address_street'], 'required'],
            [
                ['order_phone', 'order_username', 'order_patronymic', 'order_email', 'order_surname', 'order_address_locality', 'order_address_street', 'order_address_house_number'],
                'required', 'on' => self::SCENARIO_CREATE_ORDER
            ],
            [
                ['order_username', 'order_phone'],
                'required', 'on' => self::SCENARIO_CREATE_ORDER_IN_ONE_CLICK
            ],
            [['order_user_id', 'order_sity_id', 'order_status', 'order_delivery_id'], 'integer'],
            [['order_date_modification', 'order_created_at', 'order_updated_at'], 'safe'],
            [['order_comment'], 'string'],
            [['order_price_delivery', 'order_prepayment'], 'number'],
            [['order_username', 'order_patronymic', 'order_email', 'order_adress', 'order_address_street', 'order_address_locality'], 'string', 'max' => 255],
            [['order_address_apartment_number', 'order_address_house_number'], 'string', 'max' => 15],
            [['order_phone'], 'string', 'min' => 12, 'max' => 255],
            [['order_surname'], 'string', 'max' => 100],
            [['order_postcode'], 'string', 'max' => 7],
            [['order_user_ip'], 'string', 'max' => 15],
            [['order_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['order_user_id' => 'id']],
            [['order_sity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sities::class, 'targetAttribute' => ['order_sity_id' => 'id']],
            [['order_delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::class, 'targetAttribute' => ['order_delivery_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_user_id' => Yii::t('app', 'Order User ID'),
            'order_username' => Yii::t('app', 'Имя'),
            'order_patronymic' => Yii::t('app', 'Отчество'),
            'order_surname' => Yii::t('app', 'Фамилия'),
            'order_email' => Yii::t('app', 'Email'),
            'order_phone' => Yii::t('app', 'Телефон'),
            'order_sity_id' => Yii::t('app', 'Город проживания'),
            'order_adress' => Yii::t('app', 'Адрес проживания'),
            'order_address_street' => Yii::t('app', 'Улица проживания'),
            'order_postcode' => Yii::t('app', 'Индекс'),
            'order_address_locality' => Yii::t('app', 'Населенный пункт'),
            'order_address_apartment_number' => Yii::t('app', 'Квартира'),
            'order_date_modification' => Yii::t('app', 'Order Date Modification'),
            'order_status' => Yii::t('app', 'Order Status'),
            'order_comment' => Yii::t('app', 'Комментарий к заказу'),
            'order_user_ip' => Yii::t('app', 'Order User Ip'),
            'order_price_delivery' => Yii::t('app', 'Order Price Delivery'),
            'order_delivery_id' => Yii::t('app', 'Способ доставки'),
            'order_prepayment' => Yii::t('app', 'Order Prepayment'),
            'order_created_at' => Yii::t('app', 'Order Created At'),
            'order_updated_at' => Yii::t('app', 'Order Updated At'),
            'order_address_house_number' => Yii::t('app', 'Номер дома'),
        ];
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|OrderItemsQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['oi_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderUser()
    {
        return $this->hasOne(User::class, ['id' => 'order_user_id']);
    }

    /**
     * Gets query for [[OrderDelivery]].
     *
     * @return \yii\db\ActiveQuery|DeliveryQuery
     */
    public function getOrderDelivery()
    {
        return $this->hasOne(Delivery::class, ['id' => 'order_delivery_id']);
    }

    /**
     * @param $view
     * @param $password
     * @return bool
     */
    public function sendOrderLetterInOneClickToAdmin($view, $productsModel, $orderModel): bool
    {
        $result = \Yii::$app->mailer->compose(
            [
                'html' => 'views/' . $view . '/html',
                'text' => 'views/' . $view . '/text',
            ],
            ['orderModel' => $orderModel, 'productsModel' => $productsModel, 'logo' => Url::to(['/img/logo.png'])])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ". Магазин одежды"])
            ->setSubject('Заказ через "Купить в 1 клик"')
            ->send();

        // Reset layout params
        \Yii::$app->mailer->getView()->params['userName'] = null;

        return $result;
    }
}
