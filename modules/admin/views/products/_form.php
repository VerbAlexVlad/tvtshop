<?php

use app\modules\admin\models\Floor;
use app\modules\admin\models\Seasons;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use vova07\imperavi\Widget;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Products */
/* @var $description_model app\modules\admin\models\Descriptions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::begin(['id' => 'products-form-save_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]);
    } ?>

    <!--    --><?php
    //    $alertTypes = [
    //        'error'   => 'alert-danger',
    //        'danger'  => 'alert-danger',
    //        'success' => 'alert-success',
    //        'info'    => 'alert-info',
    //        'warning' => 'alert-warning'
    //    ];
    //
    //    $session = Yii::$app->session;
    //    $flashes = $session->getAllFlashes();
    //
    //    foreach ($flashes as $type => $flash) {
    //        if (!isset($alertTypes[$type])) {
    //            continue;
    //        }
    //        foreach ((array) $flash as $i => $message) {
    //            echo Html::tag(
    //                'div',
    //                $message,
    //                [
    //                    'class' => 'alert ' . $alertTypes[$type],
    //                ]
    //            );
    //
    //        }
    //
    //        $session->removeFlash($type);
    //    }
    //    ?>

    <h1 class="page-header text-center">
        <?= Html::encode($this->title) ?>
    </h1>

        <?php $form = ActiveForm::begin([
            'id' => 'products-form',
            'options' => [
                'method' => 'post',
                'class' => 'create-products-form',
                'data-pjax' => true
            ]]); ?>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('app', 'Basic information') ?>
            </div>
            <div class="panel-body">
                <?= $form->field($model, 'product_status')->widget(
                    Select2::class,
                    [
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => 'Выберите пол для категории',
                        ],
                        'data' => ['Не опубликован', 'Опубликован'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]
                ) ?>
                <?= $form->field($model, 'product_price')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'product_price_wholesale')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'product_floor')->widget(
                    Select2::class,
                    [
//                 'initValueText' => $model ? $product_model->categories->name : null,
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => 'Выберите пол для категории',
                        ],
                        'data' => Floor::find()->select(['floor_name', 'id'])->indexBy('id')->column(),
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]
                ) ?>

                <?= $form->field($model, 'product_season')->widget(
                    Select2::class,
                    [
//                 'initValueText' => $model ? $product_model->categories->name : null,
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => 'Выберите пол для категории',
                        ],
                        'data' => Seasons::find()->select(['season_name', 'id'])->indexBy('id')->column(),
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]
                ) ?>

                <?= $form->field($description_model, 'description_text')->widget(Widget::class, [
                    'settings' => [
                        'lang' => 'ru',
                        'minHeight' => 200,
                        'plugins' => [
                            'clips',
                            'fullscreen',
                        ],
                        'clips' => [
                            ['Lorem ipsum...', 'Lorem...'],
                            ['red', '<span class="label-red">red</span>'],
                            ['green', '<span class="label-green">green</span>'],
                            ['blue', '<span class="label-blue">blue</span>'],
                        ],
                    ],
                ]) ?>

                <?= $form->field($model, 'product_model_id')->widget(
                    Select2::class,
                    [
                        'initValueText' => $model->productModel->string ?? null,
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => 'Выберите пол для категории',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            // 'selectOnClose' => true,
                            'language' => [
                                'errorLoading' => new \yii\web\JsExpression("function () { return 'Произошла ошибка, перезагрузите страницу...'; }"),
                            ],
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['models/search-models']),
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
                                        "data-src" => \yii\helpers\Url::to(['models/create', 'time' => date('mm.dd.YYYY') . time()]),
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
                                        "data-src" => \yii\helpers\Url::to(['models/index', 'time' => date('mm.dd.YYYY') . time()]),
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

                <?= $form->field($model, 'product_discount')->widget(
                    Select2::class,
                    [
                        'initValueText' => $model->productDiscount->discount_name ?? null,
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => 'Выберите пол для категории',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            // 'selectOnClose' => true,
                            'language' => [
                                'errorLoading' => new \yii\web\JsExpression("function () { return 'Произошла ошибка, перезагрузите страницу...'; }"),
                            ],
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['discounts/search-discounts']),
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
                                        "data-src" => \yii\helpers\Url::to(['discounts/create', 'time' => date('mm.dd.YYYY') . time()]),
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
                                        "data-src" => \yii\helpers\Url::to(['discounts/index', 'time' => date('mm.dd.YYYY') . time()]),
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

                <?= $form->field($model, 'product_alias')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'product_title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'product_h1')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="panel-footer">
                <span style="color: red">*</span> - Поля обязательные для заполнения.
            </div>
        </div>


        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end() ?>
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::end();
    } ?>
</div>
