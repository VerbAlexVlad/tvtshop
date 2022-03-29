<?php

use andrewdanilov\fancybox\FancyboxAsset;
use app\widgets\Fancybox;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

if (Yii::$app->request->isAjax) {
    JqueryAsset::register($this);
    $this->registerJsFile('@web/js/product-main.js', ['position' => yii\web\View::POS_END]);
    $this->registerCssFile('@web/css/product-styles.css', ['position' => yii\web\View::POS_READY]);
    FancyboxAsset::register($this);
}

/* @var $model */
?>

<section class="sizes-block <?= Yii::$app->request->isAjax ? 'align-center' : '' ?>">
    <h2 class="h2_sizes"><?= Yii::$app->request->isAjax ? 'Выберите размер' : 'Размеры' ?></h2>
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'product-cart-form'
        ]
    ]); ?>

    <?php try {
        echo $form->field((new \app\models\ProductSizes()), 'id', ['options' => ['tag' => 'ul', 'class' => 'sizes-list']])
            ->radioList(
                (new \app\models\ProductSizes())->getArrayProductSizes($model->productSizes),
                [
                    'tag' => false,
                    'itemOptions' => ['tag' => 'li'],
                    'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                        if ($label->sizeForMe) {
                            $this->context->mySizes[] = $label->size_name;
                        }
                        return Html::tag('li',
                            Html::input('radio', 'ProductSizes[id]', $label->id, ['data-param-id' => $model->productSizes[$label->id]->ps_param_id, 'id' => "size-{$label->id}", 'disabled' => $label->status == 0 ? true : false]) .
                            Html::label($label->size_name, "size-{$label->id}", ['class' => $label->status == 0 ? "disabled-size" : ''])
                        );
                    }
                ]
            )
            ->label(false);
    } catch (\yii\base\InvalidConfigException $e) {
    } ?>

    <?php if (!empty(array_filter($this->context->mySizes))) {
        echo Html::tag('div', "Вам подходят размеры: " . implode(', ', array_filter($this->context->mySizes)), ['class' => 'product-size-for-me']);
    } ?>

    <section class="table-param-section">
        <div class="size-chart text-center"><strong>Размерная сетка</strong> отобразится после выбора размера</div>
    </section>

    <section class="<?= Yii::$app->request->isAjax ? 'product-button' : 'product-buttons' ?>">
        <a href="<?= \yii\helpers\Url::to('/cart/index') ?>" title="Перейти в корзину"
           class="hidden product-cart-button product-cart-link">Перейти в корзину</a>

        <?= Html::submitButton(Yii::t('app', 'Добавить в корзину'), ['class' => 'product-cart-button', 'title' => "Добавить в корзину", 'data-product_alias' => $model->product_alias]) ?>

        <?php if (!Yii::$app->request->isAjax) : ?>
            <?= Html::a('', '#',
                [
                    "class" => $model->favoritesButtonClass,
                    "title" => "Добавить в избранное",
                ]
            ); ?>
            <?php try {
                echo Fancybox::widget([
                    "fancyboxClass" => "product-one-click-button",
                    "fancyboxTitle" => "Купить в один клик",
                    "fancyboxUrl" => Url::to(['products/product-size-list-one-click', 'product_alias' => $model->product_alias]),
                ]);
            } catch (Exception $e) {
            } ?>
        <?php endif; ?>
    </section>

    <?php ActiveForm::end(); ?>
</section>