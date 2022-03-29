<?php

use app\models\Cart;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model */
?>
<div class="image">
    <a href="<?= Url::to(['products/view', 'product_alias' => $model->psProduct->product_alias]) ?>">
        <img src="<?= $model->psProduct->image->getUrl('300x') ?>" alt="">
    </a>

    <?= $model->psProduct->discountValue ?>
</div>

<div class="h2_product-info">
    <a href="<?= Url::to(['products/view', 'product_alias' => $model->psProduct->product_alias]) ?>">
        <h2 class="product-name_all-info"><?= $model->psProduct->productH1 ?></h2>
    </a>
</div>

<div class="all-info_product-info">
    <span class="size_all-info gray-style">Размер: <?= $model->psSize->size_name ?></span>
    <span class="category_all-info gray-style">Категория: <?= $model->psProduct->productModel->category->name ?></span>
    <span class="brand_all-info gray-style">Бренд: <?= $model->psProduct->productModel->brand->brand_name ?></span>
    <span class="color_all-info gray-style">Цвет: <?= $model->psProduct->implodeColors ?></span>
</div>

<div class="count_product-info">
    <span class="count-title_product-info">Количество</span>
    <div class="count_box">
        <div class="count-product-in-cart" data-type="minus">-</div>
        <label>
            <input class="inp_price" type="number" value="<?= (new Cart())->getQtyProductsInCart($model->id) ?>" min="1"
                   readonly>
        </label>
        <div class="count-product-in-cart" data-type="plus">+</div>
    </div>
</div>

<div class="price_product-info">
    <?php $currentPrice = $model->psProduct->currentPrice ?>
    <?= Html::tag('span', Yii::$app->formatter->asDecimal($currentPrice), ['class' => 'new-price']) ?>

    <?php
    if ($currentPrice !== $model->psProduct->product_price) {
        echo Html::tag('del', Yii::$app->formatter->asDecimal($model->psProduct->product_price), ['class' => 'old-price']);
    }
    ?>
</div>

<div class="delete_product-info">
    <span class="delete-product_product-info">x</span>
</div>


