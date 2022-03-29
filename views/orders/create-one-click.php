<?php

use app\models\Userparams;
use app\widgets\Alert;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $orderModel */
/* @var $productsModel */
/* @var $productSizesModel */
/* @var $userParamId */

$this->registerCssFile('@web/css/product-styles.css', ['position' => yii\web\View::POS_END]);


?>
<style>
    .table-param {
        margin-bottom: 20px;
        width: 100%;
        max-width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .table-param .text-center {
        text-align: center;
    }

    .table-param > tbody > tr > th, .table-param > tbody > tr > td {
        padding: 5px 15px;
    }

    .table-param-bordered > tbody > tr > th, .table-param-bordered > tbody > tr > td {
        border: 1px solid #ddd;
    }

    .table-param th {
        text-align: left;
        background-color: #f7f6f6;
        font-weight: normal;
        white-space: nowrap;
    }

    .atteption-link {
        color: #000;
    }

    .atteption-link a {
        border-bottom: 1px dashed;
        cursor: pointer;
        color: #F93C00;
    }


    .atteption-link a:hover {
        color: #F93C00;
    }

    .table-param-section {
        margin-bottom: 25px;
    }
</style>
<?php Pjax::begin(['class' => 'sizes-block', 'id' => 'create-one-click_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]); ?>
<?php
$js = <<< JS
    jQuery("#create-one-click-form").on("change", ".sizes-list input", function(e) {
                    var form = $('form#create-one-click-form');  

                    $.post(form.attr('action'), form.serialize())
                    .done(function(result){
                        $('#create-one-click_pjax').html(result);        
                    })
                    .fail(function(){
                        console.log('error'); 
                    });
    });
JS;
$this->registerJs($js, $position = yii\web\View::POS_END, $key = null);
?>
<section class="sizes-block <?= Yii::$app->request->isAjax ? 'align-center' : '' ?>">
    <h2 class="h2_sizes"><?= Yii::t('app', 'Оформление заказа в один клик') ?></h2>

    <?php try { ?>
        <?php $form = ActiveForm::begin([
            'id' => 'create-one-click-form',
            'options' => [
                'method' => 'get',
                'class' => 'create-one-click-form',
                'data-pjax' => true
            ]
        ]); ?>

        <?= Html::activeHiddenInput($productsModel, 'product_alias'); ?>

        <?= $form->field($productSizesModel, 'id', ['options' => ['tag' => 'ul', 'class' => 'sizes-list']])
            ->radioList(
                $productsModel->productSizes,
                [
                    'tag' => false,
                    'itemOptions' => ['tag' => 'li'],
                    'item' => function ($index, $label, $name, $checked, $value) use ($userParamId) {
                        if ($userParamId && !empty($label->params[0]->paramUserparam)) {
                            $label->psSize->sizeForMe = true;
                            $this->context->mySizes[] = $label->psSize->size_name;
                        }

                        return Html::tag('li',
                            Html::activeInput('radio', $label, 'id', ['checked' => $checked, 'data-param-id' => $label->ps_param_id, 'id' => "one-click-size-{$label->ps_size_id}", 'disabled' => $label->ps_status == 0 ? true : false]) .
                            Html::label($label->psSize->size_name, "one-click-size-{$label->ps_size_id}", ['class' => $label->ps_status == 0 ? "disabled-size" : ''])
                        );
                    }
                ]
            )
            ->label(false)
        ?>


        <?php if (!empty(array_filter($this->context->mySizes))) {
            echo Html::tag('div', "Вам подходят размеры: " . implode(', ', array_filter($this->context->mySizes)), ['class' => 'product-size-for-me']);
        } ?>

        <section class="table-param-section">
            <?= Alert::widget() ?>
            <?php if (!$productSizesModel->id) { ?>
                <div class="size-chart text-center"><strong>Размерная сетка</strong> отобразится после выбора размера
                </div>
            <?php } else { ?>
                <?php
                if ($userParamId) {
                    $userParam = Userparams::find()
                        ->where(['userparam_param_num' => $userParamId])
                        ->indexBy('userparam_parameters_id')
                        ->asArray()
                        ->limit(20)
                        ->all();
                }
                ?>
                <table style="overflow: auto;" class="table-param table-param-bordered table-dimension-ruler">
                    <tbody class="text-center">
                    <tr>
                        <th rowspan="2"></th>
                        <th colspan="2" class="text-center">Параметры</th>
                    </tr>
                    <tr>
                        <th class="text-center">Товара</th>
                        <th class="text-center" style="white-space: normal">Ваши</th>
                    </tr>

                    <?php foreach ($productsModel->productSizes[$productSizesModel->id]->params as $item) { ?>
                        <tr>
                            <th><?= $item->paramParameters->parameter_name ?></th>
                            <td><?= $item->param_value ?> см</td>
                            <td>
                            <span class="atteption-link">
                                <?php if (isset($userParam[$item['param_parameters_id']])) { ?>
                                    <?= $userParam[$item['param_parameters_id']]['userparam_value'] ?> см
                                <?php } else { ?>
                                    <a href="<?= \yii\helpers\Url::to(['userparams/update']) ?>"
                                       title="Указать параметры">Указать</a>
                                <?php } ?>
                            </span>
                            </td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            <?php } ?>

        </section>

        <section class="inputs-one-click">
            <?= $form->field($orderModel, 'order_username')->textInput(['maxlength' => true])->label('Укажите Ваше имя') ?>
            <?= $form->field($orderModel, 'order_phone')
                ->widget(\yii\widgets\MaskedInput::class, [
                    'mask' => '+79999999999',
                    'options' => [
                        'placeholder' => ('Контактный телефон')
                    ],
                    'clientOptions' => [
                        'clearIncomplete' => true
                    ]
                ])->label('Укажите номер Вашего телефона') ?>
        </section>

        <section class="product-button">
            <?= Html::submitButton(Yii::t('app', 'Оформить заказ в один клик'), ['class' => 'product-cart-button', 'name' => 'button', 'value' => 1, 'data-pjax' => 0, 'title' => "Оформить заказ в один клик", 'data-product_alias' => $productsModel->product_alias]) ?>
        </section>
        <?php ActiveForm::end(); ?>
    <?php } catch (\yii\base\InvalidConfigException $e) {
    } ?>
</section>
<?php Pjax::end(); ?>
