<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $dataProvider */
/* @var $sizes_in_cart */
/* @var $model */


// $token = "38e30d8acef6f0b79a87828b2d97cde54dc7a5e2";
// $dadata = new \Dadata\DadataClient($token, null);
// $result = $dadata->suggest("address", "685000 пролетарская 4");

// dd($result);
?>
<?php \yii\widgets\Pjax::begin(['class' => 'create-order', 'id' => 'create-order-form_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]); ?>
<?php $form = ActiveForm::begin([
    'fieldConfig' => ['options' => ['class' => 'form-group form-horizontal-group']],
    'id' => 'create-order-form',
    'options' => [
        'method' => 'get',
        'class' => 'create-order-form',
        'data-pjax' => true
    ]
]); ?>
<main class="order-product-block container">
    <div class="products-category">
        <div class="order-block">
            <?= $form->field($model, 'order_surname')->textInput(['maxlength' => true, 'placeholder' => 'Фамилию']) ?>

            <?= $form->field($model, 'order_username')->textInput(['maxlength' => true, 'placeholder' => 'Имя'])->label() ?>

            <?= $form->field($model, 'order_patronymic')->textInput(['maxlength' => true, 'placeholder' => 'Отчество']) ?>

            <?= $form->field($model, 'order_email')->textInput(['maxlength' => true, 'placeholder' => 'Укажите Ваш электронный адрес']) ?>

            <?php try {
                echo $form->field($model, 'order_phone')->widget(MaskedInput::class, [
                    'mask' => '+79999999999',
                    'options' => [
                        'placeholder' => 'Телефон',
                    ]
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
            } ?>

            <?php try {
                echo $form->field($model, "order_address_locality")->widget(
                    Select2::class, [
                    'initValueText' => $model->order_address_locality ?? false,
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => "Пример: 101000, г Москва",
                    ],
                    'hideSearch' => false,
                    'pluginOptions' => [
                        //                       'tags' => true,
                        'allowClear' => true,
                        // 'selectOnClose' => true,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Идет поиск...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['orders/search-locality']),
                            'dataType' => 'json',
                            'cache' => false,
                            'data' => new JsExpression('function(params) { 
                                    return {
                                        q:params.term,
                                    }; 
                                }')
                        ],
                    ],
                    'pluginEvents' => [
                        "change" => "function(e) {
                                     $('#orders-order_address_street').prop('selectedIndex', 0).trigger('change');
                                     $('#orders-order_address_house_number').prop('selectedIndex', 0).trigger('change');
                                      var form = $('form#create-order-form');
                                      var formData = form.serialize();
                                      $.pjax.reload({container:'#create-order-form_pjax', method:'post', data:formData});
                                }"
                    ],
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>

            <div class="address-group">
                <div class="hidden-block"></div>
                <?php try {
                    echo $form->field($model, "order_address_street", ['options' => ['class' => 'form-group address-group-item']])->widget(
                        Select2::class, [
                        'initValueText' => $model->order_address_street ?? false,
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Улица',
                        ],
                        'hideSearch' => false,
                        'pluginOptions' => [

                            'allowClear' => true,
                            // 'selectOnClose' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Произошла ошибка, перезагрузите страницу...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['orders/search-street']),
                                'dataType' => 'json',
                                'cache' => false,
                                'data' => new JsExpression('function(params) {
                                        return {
                                            q:params.term,
                                            locality: $("form #orders-order_address_locality").val(),
                                        }; 
                                    }')
                            ],
                        ],
                        'pluginEvents' => [
                            "select2:selecting" => "function() { 
                                    $('#orders-order_address_house_number').val('').trigger('change');
                                }",
                            "select2:unselecting" => "function() {
                                    $('#orders-order_address_house_number').val('').trigger('change');
                                }",
                        ],
                    ])->label(false);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                ?>
                <?php try {
                    echo $form->field($model, "order_address_house_number", ['options' => ['class' => 'form-group address-group-item']])->widget(
                        Select2::class, [
                        'initValueText' => $model->order_address_house_number ?? false,
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Дом',
                        ],
                        'hideSearch' => false,
                        'pluginOptions' => [
                            //                       'tags' => true,
                            'allowClear' => true,
                            // 'selectOnClose' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Произошла ошибка, перезагрузите страницу...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['orders/search-house-number']),
                                'dataType' => 'json',
                                'cache' => false,
                                'data' => new JsExpression('function(params) { 
                                            return {
                                                q:params.term,
                                                locality: $("form #orders-order_address_locality").val(),
                                                street: $("form #orders-order_address_street").val(),
                                            }; 
                                        }')
                            ],
                        ],

                    ])->label(false);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                ?>
                <?= $form->field($model, 'order_address_apartment_number', ['options' => ['class' => 'form-group field-orders-order_address_apartment_number']])->textInput(['maxlength' => true, 'placeholder' => 'Квартира', 'class' => 'address-group-item'])->label(false) ?>
            </div>

            <div class="delivery-group form-group form-horizontal-group">
                <?php if($calcInfo) {
                    echo $calcInfo->getPayNds();
                } ?>
            </div>
            <?= $form->field($model, 'order_comment')->textarea(['maxlength' => true, 'style' => "resize: none;", 'rows' => 5, 'placeholder' => 'Введите отзыв к заказу']) ?>
        </div>
    </div>

    <div class="summary-information">
        <?php if ($dataProvider) { ?>
            <?php
            $price = 0;
            $currentPrice = 0;
            foreach ($dataProvider->getModels() as $item) {
                if (!Yii::$app->user->isGuest) {
                    $value = $sizes_in_cart[$item->id]->qty;
                } else {
                    $value = $_SESSION['sizes_in_cart'][$item->id]['qty'] ?? 1;
                }
                $price += $item->psProduct->product_price * $value;
                $currentPrice += $item->psProduct->currentPrice * $value;
            }
            ?>

            <div class="sticky-summary-information">
                <div class="summary item_summary-information">
                    <span>Итого</span>
                    <span class="product_discount"><?= $currentPrice ?></span>
                </div>
                <div class="item_summary-information">
                    <span>Цена без скидки</span>
                    <span class="product_price"><?= $price ?></span>
                </div>
                <div class="item_summary-information">
                    <span>Скидка</span>
                    <span class="summ-discount"><?= $price - $currentPrice ?></span>
                </div>

                <?= Html::submitButton(Yii::t('app', 'Оформить заказ и перейти к оплате'), ['class' => 'product-order-button product-order-link']) ?>
            </div>
        <?php } ?>
    </div>
</main>
<?php ActiveForm::end(); ?>
<?php \yii\widgets\Pjax::end(); ?>
