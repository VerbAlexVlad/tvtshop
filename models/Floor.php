<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%floor}}".
 *
 * @property int $id
 * @property string|null $floor_name
 *
 * @property Products[] $products
 */
class Floor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%floor}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['floor_name'], 'string', 'max' => 8],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'floor_name' => Yii::t('app', 'Floor Name'),
        ];
    }

    /**
     * Получение пола пользователя
     * @return mixed|null
     */
    public function getUserFloor()
    {
        if (!Yii::$app->user->isGuest) {
            $floor = Yii::$app->user->identity->floor ?? null;
        } else {
            $floor = $_SESSION['user']['floor'] ?? null;
        }

        return $floor;
    }

    /**
     * Сохранение пола пользователя либо в базу (авторизованный), либо в сессию
     * @param $floor
     * @return void
     */
    public function setUserFloor($floor)
    {
        if (!Yii::$app->user->isGuest) {
            if($user = User::findOne(Yii::$app->user->id)){
                $user->floor = $floor;
                $user->save();
            }
        } else {
            $_SESSION['user']['floor'] = $floor;
        }
    }


    /**
     * Gets query for [[Products]].
     * @return ActiveQuery
     */
    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Products::class, ['product_floor' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return ActiveQuery
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Categories::class, ['floor' => 'id']);
    }

    /**
     * Получение id пола пользователя
     * @param $all
     * @return int
     */
    public function floorId($all): int
    {
        switch ($all){
            case 'man':
                return 1;
            case 'woman':
                return 2;
            default:
                return 0;
        }
    }
}
