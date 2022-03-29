<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SearchOrders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'order_user_id') ?>

    <?= $form->field($model, 'order_username') ?>

    <?= $form->field($model, 'order_patronymic') ?>

    <?= $form->field($model, 'order_surname') ?>

    <?php // echo $form->field($model, 'order_email') ?>

    <?php // echo $form->field($model, 'order_phone') ?>

    <?php // echo $form->field($model, 'order_sity_id') ?>

    <?php // echo $form->field($model, 'order_adress') ?>

    <?php // echo $form->field($model, 'order_postcode') ?>

    <?php // echo $form->field($model, 'order_date_modification') ?>

    <?php // echo $form->field($model, 'order_status') ?>

    <?php // echo $form->field($model, 'order_comment') ?>

    <?php // echo $form->field($model, 'order_user_ip') ?>

    <?php // echo $form->field($model, 'order_price_delivery') ?>

    <?php // echo $form->field($model, 'order_delivery_id') ?>

    <?php // echo $form->field($model, 'order_prepayment') ?>

    <?php // echo $form->field($model, 'order_created_at') ?>

    <?php // echo $form->field($model, 'order_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
