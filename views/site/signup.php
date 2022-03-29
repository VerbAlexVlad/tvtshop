<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>









<div class="site-signup">
    <?php \yii\widgets\Pjax::begin(['class' => 'sizes-block', 'id' => 'create-one-click_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]); ?>
        <div class="title container">
            <h1 class="h1_title text-center"><?= Html::encode($this->title) ?></h1>
        </div>

        <?php $form = ActiveForm::begin([
            'fieldConfig' => ['options' => ['class' => 'form-group form-horizontal-group']],
            'id' => 'signup-form',
            'options' => [
                'method' => 'get',
                'class' => 'signup-form',
                'data-pjax' => true
            ]
        ]); ?>
        <main class="order-product-block container">
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group login-button-group">
                <div class="login-button">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button', 'data-pjax' => false]) ?>
                </div>
                <div class="login-sendemail">
                    <?= Html::a('Или войти', \yii\helpers\Url::to('site/login'), ['data-pjax' => true]) ?>
                </div>
            </div>
        </main>
        <?php ActiveForm::end(); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
