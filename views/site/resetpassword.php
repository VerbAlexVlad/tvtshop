<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ResetPasswordForm */
/* @var $form ActiveForm */
$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-resetpassword">
    <?php \yii\widgets\Pjax::begin(['class' => 'sizes-block', 'id' => 'create-one-click_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]); ?>
        <div class="title container">
            <h1 class="h1_title text-center"><?= Html::encode($this->title) ?></h1>
        </div>

        <?php $form = ActiveForm::begin([
            'fieldConfig' => ['options' => ['class' => 'form-group form-horizontal-group']],
            'id' => 'resetpassword-form',
            'options' => [
                'method' => 'get',
                'class' => 'resetpassword-form',
                'data-pjax' => true
            ]
        ]); ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Введите новый пароль'])->label(false) ?>

    <div class="form-group login-button-group">
        <div class="login-button">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button', 'data-pjax' => false]) ?>
        </div>
        <div class="login-sendemail">
            <?= Html::a('Или войти', \yii\helpers\Url::to('site/login'), ['data-pjax' => true]) ?>
        </div>
    </div>
        <?php ActiveForm::end(); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>