<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Orders'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_user_id',
            'order_username',
            'order_patronymic',
            'order_surname',
            //'order_email:email',
            //'order_phone',
            //'order_sity_id',
            //'order_adress',
            //'order_postcode',
            //'order_date_modification',
            //'order_status',
            //'order_comment:ntext',
            //'order_user_ip',
            //'order_price_delivery',
            //'order_delivery_id',
            //'order_prepayment',
            //'order_created_at',
            //'order_updated_at',
            //'order_address_locality',
            //'order_address_street',
            //'order_address_house_number',
            //'order_address_apartment_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
