<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<style>
    .parameters-block{
        margin-bottom: 25px;
        padding-bottom: 25px;
        text-align: center;
    }
    .parameters-block label{
        white-space: nowrap;
    }
    .parameters-block h2{
        margin: 0 0 25px;
    }
    .parameters-block .btn {
        padding: 10px;
        background-color: #fff;
        border: 1px solid #A0A0A0;
        display: inline-block;
    }
    .parameters-list form {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }
    .parameter-info {
        margin-bottom: 25px;
    }
    .parameters-list .form-group {
        width: 100%;
    }
    .parameter-item {
        margin-bottom: 25px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: space-between;
        flex-basis: calc(20% - 10px);
        text-align: center;
    }

    .parameter-item img {
        width: 100%;
        margin-bottom: 15px;
    }

    .parameter-item:not(:nth-child(6n)) {
        margin-right: 12px;
    }

    @media (max-width: 640px) {
        .parameter-item {
            flex-basis: calc(30% - 10px);
        }
    }
</style>
<div class="parameters-block">
    <div class="parameters-list">
        <?php $form = ActiveForm::begin(['action' => '/userparams/update']); ?>

        <?php foreach ($param_list as $param_item) { ?>
            <div class="parameter-item">
                <?php $manyImg = $param_item->image;?>

                <?= Html::img($manyImg->getUrl('x300'), ['class' => 'parameter-image']) ?>
                <?= $form->field($param_item, "id[" . $param_item->id . "]")
                    ->textInput([
                        'placeholder' => 'в см',
                        'step' => '1',
                        'min' => '0',
                        'max' => '999',
                        'value' => $userParam_list[$param_item->id]->userparam_value ?? false
                    ])
                    ->label($param_item->parameter_name) ?>
            </div>
        <?php } ?>
      
        <div class="parameter-item">
            <?= Html::img('/img/floor.jpg', ['class' => 'parameter-image']) ?>
            <div class="form-group">
                <?= Html::label('Ваш пол', "user-floor") ?>

                <?= Html::dropDownList('floor', \app\models\Floor::getUserFloor(), ['' => 'Укажите пол', 1 => 'Мужской', 2 => 'Женский', ], ['required' => true, 'id'=>"user-floor"]) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="parameter-info">
            <span>* Укажите <b>ОДИН</b> или <b>НЕСКОЛЬКО</b> параметров и нажмите на "Начать поиск"</span>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Начать поиск подходящей одежды'), ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>