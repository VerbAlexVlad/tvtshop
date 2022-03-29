<?php

use andrewdanilov\fancybox\FancyboxAsset;
use app\widgets\Alert;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\Pjax;

/* @var $orderModel */
/* @var $productsModel */
/* @var $productSizesModel */

JqueryAsset::register($this);
$this->registerJsFile('@web/js/create-one-click-main.js', ['position' => yii\web\View::POS_END]);
$this->registerCssFile('@web/css/product-styles.css', ['position' => yii\web\View::POS_READY]);
FancyboxAsset::register($this);
?>
<section class="sizes-block <?= Yii::$app->request->isAjax ? 'align-center' : '' ?>">
    <?php Pjax::begin(['enablePushState' => false]); ?>
    <h2 class="h2_sizes"><?= Yii::t('app', 'Оформление заказа в один клик') ?></h2>

    <?php $form = ActiveForm::begin([
//        'type' => ActiveForm::TYPE_FLOATING,
        'id' => 'one-click-form',
        'options' => [
            'method' => 'get',
            'class' => 'create-one-click-form',
            'data-pjax' => true
        ]
    ]); ?>

    <?= Html::activeHiddenInput($productsModel, 'product_alias'); ?>

    <?php try {
        echo $form->field($productSizesModel, 'id', ['options' => ['tag' => 'ul', 'class' => 'sizes-list']])
            ->radioList(
                $productSizesModel->getArrayProductSizes($productsModel->productSizes),
                [
                    'tag' => false,
                    'itemOptions' => ['tag' => 'li'],
                    'item' => function ($index, $label, $name, $checked, $value) use ($productsModel) {
                        if ($label->sizeForMe) {
                            $this->context->mySizes[] = $label->size_name;
                        }
                        return Html::tag('li',
                            Html::input('radio', 'ProductSizes[id]', $label->id, ['data-param-id' => $productsModel->productSizes[$label->id]->ps_param_id, 'id' => "one-click-size-{$label->id}", 'disabled' => $label->status == 0 ? true : false]) .
                            Html::label($label->size_name, "one-click-size-{$label->id}", ['class' => $label->status == 0 ? "disabled-size" : ''])
                        );
                    }
                ]
            )
            ->label(false);
    } catch (\yii\base\InvalidConfigException $e) {
    }
    ?>

    <?php if (!empty(array_filter($this->context->mySizes))) {
        echo Html::tag('div', "Вам подходят размеры: " . implode(', ', array_filter($this->context->mySizes)), ['class' => 'product-size-for-me']);
    } ?>

    <section class="table-param-section">
        <?= Alert::widget() ?>
        <div class="size-chart text-center"><strong>Размерная сетка</strong> отобразится после выбора размера</div>
    </section>

    <section class="inputs-one-click">
        <?= $form->field($orderModel, 'order_username')->textInput(['maxlength' => true])->label('Укажите Ваше имя') ?>
        <?= $form->field($orderModel, 'order_phone')->textInput(['maxlength' => true])->label('Укажите номер Вашего телефона') ?>
    </section>

    <section class="product-button">
        <?= Html::submitButton(Yii::t('app', 'Оформить заказ в один клик'), ['class' => 'product-cart-button', 'title' => "Оформить заказ в один клик", 'data-product_alias' => $productsModel->product_alias]) ?>
    </section>
    <?php ActiveForm::end(); ?>

    <script>
        $('form.create-one-click-form').on('change', 'input:radio', function (e) {
            console.log('2');
            let cartButton = $('button.product-cart-button')[0];
            cartButton.classList.remove('hidden');
            let cartLink = $('a.product-cart-button')[0];
            if (cartLink != undefined) {
                cartLink.classList.add('hidden');
            }
            let param_id = $(this).data('param-id');

            $.ajax({
                url: '/products/product-size-param-list',
                type: 'get',
                data: {
                    param_id: param_id
                },
                success: function (res) {
                    if (!res) alert('Ошибка!!!');

                    $('.table-param-section').html(res);
                },
                error: function () {
                    alert('Ошибка!');
                }
            });
        });
    </script>

    <?php Pjax::end(); ?>
</section>