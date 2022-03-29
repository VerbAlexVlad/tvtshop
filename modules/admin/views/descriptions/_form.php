<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Descriptions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="descriptions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'description_product_id')->textInput() ?>

    <?= $form->field($model, 'description_text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
