<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Orders */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_user_id',
            'order_username',
            'order_patronymic',
            'order_surname',
            'order_email:email',
            'order_phone',
            'order_sity_id',
            'order_adress',
            'order_postcode',
            'order_date_modification',
            'order_status',
            'order_comment:ntext',
            'order_user_ip',
            'order_price_delivery',
            'order_delivery_id',
            'order_prepayment',
            'order_created_at',
            'order_updated_at',
            'order_address_locality',
            'order_address_street',
            'order_address_house_number',
            'order_address_apartment_number',
        ],
    ]) ?>

</div>
