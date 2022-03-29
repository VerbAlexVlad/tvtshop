<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'product_status') ?>

    <?= $form->field($model, 'product_price') ?>

    <?= $form->field($model, 'product_price_wholesale') ?>

    <?= $form->field($model, 'product_floor') ?>

    <?php // echo $form->field($model, 'product_season') ?>

    <?php // echo $form->field($model, 'product_model_id') ?>

    <?php // echo $form->field($model, 'product_datecreate') ?>

    <?php // echo $form->field($model, 'product_shop_id') ?>

    <?php // echo $form->field($model, 'product_discount') ?>

    <?php // echo $form->field($model, 'product_alias') ?>

    <?php // echo $form->field($model, 'product_description_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
