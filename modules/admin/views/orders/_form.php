<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_user_id')->textInput() ?>

    <?= $form->field($model, 'order_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_sity_id')->textInput() ?>

    <?= $form->field($model, 'order_adress')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_postcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_date_modification')->textInput() ?>

    <?= $form->field($model, 'order_status')->textInput() ?>

    <?= $form->field($model, 'order_comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'order_user_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_price_delivery')->textInput() ?>

    <?= $form->field($model, 'order_delivery_id')->textInput() ?>

    <?= $form->field($model, 'order_prepayment')->textInput() ?>

    <?= $form->field($model, 'order_created_at')->textInput() ?>

    <?= $form->field($model, 'order_updated_at')->textInput() ?>

    <?= $form->field($model, 'order_address_locality')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_address_street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_address_house_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_address_apartment_number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
