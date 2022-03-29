<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
/* @var $model app\models\SendemailForm */
$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-send-email">
    <?php \yii\widgets\Pjax::begin(['class' => 'sizes-block', 'id' => 'create-one-click_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]); ?>
        <div class="title container">
            <h1 class="h1_title text-center"><?= Html::encode($this->title) ?></h1>
        </div>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => ['options' => ['class' => 'form-group form-horizontal-group']],
            'id' => 'send-email-form',
            'options' => [
                'method' => 'get',
                'class' => 'send-email-form',
                'data-pjax' => true
            ]
        ]); ?>
            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Пример: AlexVlad@gmail.com', 'required' => 'required']); ?>

            <div class="form-group login-button-group">
                <div class="login-button">
                    <?= Html::submitButton('Восстановить', ['class' => 'btn btn-primary', 'name' => 'login-button', 'data-pjax' => false]) ?>
                </div>
                <div class="login-sendemail">
                    <?= Html::a('Или войти', \yii\helpers\Url::to('site/login')) ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>