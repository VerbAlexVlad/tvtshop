<?php

use app\modules\admin\models\Floor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'image')->fileInput() ?>
  
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_singular_category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'parent_id')->textInput() ?>
    <?= $form->field($model, 'parent_id')->widget(
        Select2::class, 
            [
                'initValueText' => !$model->isNewRecord ? $model->parentForId->name : null,
                'hideSearch'    => false,
                'options'       => [
                    'placeholder' => 'Выберите категорию товара',
                ],
                'theme'         => Select2::THEME_DEFAULT,
                'pluginOptions' => [
//                     'tags'       => true,
                    'allowClear' => true,
                    'language'   => [
                        'errorLoading' => new JsExpression("function () { return 'Ожидание результатов...'; }"),
                    ],
                    'ajax'       => [
                        'url'      => \yii\helpers\Url::to(['categories/category-list']),
                        'dataType' => 'json',
                        'cache' => false,
                        'data' => new JsExpression('function(params) { 
                            return {
                                q:params.term
                            }; 
                        }')
                    ],
                ],
            ]
    ); ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'categories_extended_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'floor')->widget(
        Select2::class, 
            [
//                 'initValueText' => $model ? $product_model->categories->name : null,
                'hideSearch'    => true,
                'options'       => [
                    'placeholder' => 'Выберите пол для категории',
                ],
                'data' => Floor::find()->select(['floor_name', 'id'])->indexBy('id')->column(),
                'theme'         => Select2::THEME_DEFAULT,
                'pluginOptions' => [
//                     'tags'       => true,
                    'allowClear' => true,
                ],
            ]
    ); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Редактировать'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
