<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Models */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="models-form">
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::begin(['id' => 'models-form-save_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]);
    } ?>

    <h1 class="page-header text-center">
        <?= Html::encode($this->title) ?>
    </h1>

    <?php  (new app\modules\admin\widgets\Alert)->begin(); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'models-form',
        'options' => [
            'method' => 'post',
            'class' => 'create-models-form',
            'data-pjax' => true
        ]]); ?>

    <?= $form->field($model, 'model_name')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'category_id')->widget(
        \kartik\select2\Select2::class,
        [
            'initValueText' => $model->brand->brand_name ?? null,
            'hideSearch' => false,
            'options' => [
                'placeholder' => 'Выберите категорию товара',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                // 'selectOnClose' => true,
                'language' => [
                    'errorLoading' => new \yii\web\JsExpression("function () { return 'Произошла ошибка, перезагрузите страницу...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['categories/category-list']),
                    'dataType' => 'json',
                    'cache' => false,
                    'data' => new \yii\web\JsExpression('function(params) { 
                                return {
                                    q:params.term,
                                }; 
                            }')
                ],
            ],
            'addon' => [
                'append' => [
                    'content' => Html::a('<i class="fa fa-plus"></i>', '#',
                        [
                            "class" => 'btn btn-default',
                            "title" => 'Добавить позицию',
                            "data-src" => \yii\helpers\Url::to(['categories/create', 'time' => date('mm.dd.YYYY') . time()]),
                            "data-fancybox" => true,
                            "data-type" => "ajax",
                            "data-options" => '{"touch" : false, "cache": false}',
                        ]
                    ),
                    'asButton' => true
                ],
                'prepend' => [
                    'content' => Html::a('<i class="fa fa-bars"></i>', '#',
                        [
                            "class" => 'btn btn-default',
                            "title" => 'Показать полный список',
                            "data-src" => \yii\helpers\Url::to(['categories/index', 'time' => date('mm.dd.YYYY') . time()]),
                            "data-fancybox" => true,
                            "data-type" => "ajax",
                            "data-options" => '{"touch" : false, "cache": false}',
                        ]
                    ),
                    'asButton' => true
                ]
            ]
        ]
    ) ?>

    <?= $form->field($model, 'brand_id')->widget(
        \kartik\select2\Select2::class,
        [
            'initValueText' => $model->brand->brand_name ?? null,
            'hideSearch' => false,
            'options' => [
                'placeholder' => 'Выберите бренд товара',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                // 'selectOnClose' => true,
                'language' => [
                    'errorLoading' => new \yii\web\JsExpression("function () { return 'Произошла ошибка, перезагрузите страницу...'; }"),
                ],
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['brands/search-brands']),
                    'dataType' => 'json',
                    'cache' => false,
                    'data' => new \yii\web\JsExpression('function(params) { 
                                return {
                                    q:params.term,
                                }; 
                            }')
                ],
            ],
            'addon' => [
                'append' => [
                    'content' => Html::a('<i class="fa fa-plus"></i>', '#',
                        [
                            "class" => 'btn btn-default',
                            "title" => 'Добавить позицию',
                            "data-src" => \yii\helpers\Url::to(['brands/create', 'time' => date('mm.dd.YYYY') . time()]),
                            "data-fancybox" => true,
                            "data-type" => "ajax",
                            "data-options" => '{"touch" : false, "cache": false}',
                        ]
                    ),
                    'asButton' => true
                ],
                'prepend' => [
                    'content' => Html::a('<i class="fa fa-bars"></i>', '#',
                        [
                            "class" => 'btn btn-default',
                            "title" => 'Показать полный список',
                            "data-src" => \yii\helpers\Url::to(['brands/index', 'time' => date('mm.dd.YYYY') . time()]),
                            "data-fancybox" => true,
                            "data-type" => "ajax",
                            "data-options" => '{"touch" : false, "cache": false}',
                        ]
                    ),
                    'asButton' => true
                ]
            ]
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::end();
    } ?>
</div>
