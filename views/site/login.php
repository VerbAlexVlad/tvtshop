<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use kartik\form\ActiveForm;

$this->title = 'Войти';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?php \yii\widgets\Pjax::begin(['class' => 'sizes-block', 'id' => 'create-one-click_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]); ?>
        <div class="title container">
            <h1 class="h1_title text-center"><?= Html::encode($this->title) ?></h1>
        </div>

        <?php $form = ActiveForm::begin([
            'fieldConfig' => ['options' => ['class' => 'form-group form-horizontal-group']],
            'id' => 'login-form',
            'options' => [
                'method' => 'get',
                'class' => 'login-form',
                'data-pjax' => true
            ]
        ]); ?>
        <main class="order-product-block container">
            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => $model->attributeLabels()['email']]) ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->attributeLabels()['password']]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group login-button-group">
                <div class="login-button">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button', 'data-pjax' => false]) ?>
                </div>
                <div class="login-sendemail">
                    <?= Html::a('Забыли пароль?', \yii\helpers\Url::to('site/sendemail'), ['data-pjax' => true]) ?>
                </div>
                <div class="login-signup">
                    <?= Html::a('Или зарегистрироваться', \yii\helpers\Url::to(['site/signup']), ['data-pjax' => true]) ?>
                </div>
            </div>

        </main>
        <?php ActiveForm::end(); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>
