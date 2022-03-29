<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FavoritesProducts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="favorites-products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fp_user_id')->textInput() ?>

    <?= $form->field($model, 'fp_product_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
