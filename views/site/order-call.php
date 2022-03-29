<?php
use app\widgets\Alert;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;

/* @var $thisUrl string */
/* @var $model app\models\OrderCall */
JqueryAsset::register($this);

if (Yii::$app->request->isAjax) {
    $this->registerCssFile('@web/css/order-call-styles.css', ['position' => yii\web\View::POS_READY]);
}

?>
<div class="order-call-group">
    <?php Pjax::begin(['enablePushState' => false]); ?>
    <div class="container">
        <?php try { echo Alert::widget(); } catch (Exception $e) {} ?>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'order-call-form',
        'options' => [
            'method' => 'get',
            'class' => 'create-order-call-form',
            'data-pjax' => true
        ]
    ]); ?>

    <?= Html::hiddenInput('urlPageFromSending', $thisUrl) ?>
    <?= $form->field($model, 'oc_user_name')->textInput(['maxlength' => true, 'class' => 'input-order-call', 'placeholder' => 'Укажите Ваше Имя']) ?>
    <?= $form->field($model, 'oc_user_phone')
        ->widget(\yii\widgets\MaskedInput::class, [
            'mask' => '+79999999999',
            'options' => [
                'class' => 'input-order-call',
                'placeholder' => ('Контактный телефон')
            ],
            'clientOptions' => [
                'clearIncomplete' => true
            ]
        ])->label('Укажите номер Вашего телефона') ?>
    <?= $form->field($model, 'oc_question')->textarea(['rows' => 5, 'maxlength' => true, 'class' => 'input-order-call', 'placeholder' => 'Укажите коментарий']) ?>

    <div class="form-group">
        <?= $model->isNewRecord ? Html::submitButton(Yii::t('app', 'Заказать звонок'), ['class' => 'btn btn-primary button-order-call']) : '' ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
