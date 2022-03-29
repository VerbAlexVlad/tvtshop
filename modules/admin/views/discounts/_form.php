<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Discounts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="discounts-form">
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::begin(['id' => 'discounts-form-save_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]);
    } ?>
    <h1 class="page-header text-center">
        <?= Html::encode($this->title) ?>
    </h1>

    <?php  (new app\modules\admin\widgets\Alert)->begin(); ?>

    <?php try { ?>
        <?php $form = ActiveForm::begin([
            'id' => 'discounts-form',
            'options' => [
                'method' => 'post',
                'class' => 'create-discounts-form',
                'data-pjax' => true
            ]]); ?>

        <?= $form->field($model, 'discount_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'discount_value')->textInput() ?>

        <?= $form->field($model, 'discount_unit_id')->textInput() ?>

        <?= $form->field($model, 'discount_from_date')->textInput() ?>

        <?= $form->field($model, 'discount_to_date')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'name' => 'button', 'value' => 1, 'data-pjax' => 0]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php } catch (Exception $e) {
        dd($e->getMessage() . "\n" . $e->getLine() . "\n" . $e->getFile() . "\n" . $e->getTraceAsString(), 1);
    } ?>
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::end();
    } ?>
</div>
