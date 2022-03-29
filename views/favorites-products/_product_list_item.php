<?php
use yii\helpers\Html;
use yii\helpers\Url;
use toriphes\lazyload\LazyLoad;
?>

<div class="image">
    <a href="<?= Url::to(['products/view', 'product_alias' => $model->product_alias]) ?>">
        <?= LazyLoad::widget(['src' => $model->image->getUrl('300x')]); ?>
    </a>

    <?= $model->discountValue ?>
</div>

<div class="h2_product-info">
    <h2 class="product-name_all-info"><?= $model->productH1 ?></h2>
</div>

<div class="all-info_product-info">
    <span class="category_all-info gray-style">Артикул: <?= $model->id ?></span>
    <span class="category_all-info gray-style">Категория: <?= $model->productModel->category->name ?></span>
    <span class="brand_all-info gray-style">Бренд: <?= $model->productModel->brand->brand_name ?></span>
    <span class="color_all-info gray-style">Цвет: <?= $model->implodeColors ?></span>

</div>

<div class="price_product-info">
    <?php $currentPrice = $model->currentPrice ?>
    <?= Html::tag('span', Yii::$app->formatter->asDecimal($currentPrice), ['class' => 'new-price']) ?>

    <?php
    if ($currentPrice !== $model->product_price) {
        echo Html::tag('del', Yii::$app->formatter->asDecimal($model->product_price), ['class' => 'old-price']);
    }
    ?>

</div>

<div class="cart_product-info">
    <?= \app\widgets\Fancybox::widget([
        "fancyboxClass" => "product-in-cart-button",
        "fancyboxText" => "Добавить в корзину",
        "fancyboxTitle" => "Добавить в корзину",
        "fancyboxUrl" => Url::to(['products/product-size-list', 'product_alias' => $model->product_alias]),
    ]) ?>
</div>
<div class="delete_product-info">
    <span class="delete-product_product-info">x</span>
</div>



