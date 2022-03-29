<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Userparams */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="userparams-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userparam_parameters_id')->textInput() ?>

    <?= $form->field($model, 'userparam_value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'userparam_param_num')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
