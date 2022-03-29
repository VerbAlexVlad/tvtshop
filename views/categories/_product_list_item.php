<?php

use app\widgets\Fancybox;
use toriphes\lazyload\LazyLoad;
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $model */
/* @var $favoritesProducts */
?>
<div class="image">
    <a class="link_image" href="<?= Url::to(['products/view', 'product_alias' => $model->product_alias]) ?>">
        <!--        --><?php
        //        $format = pathinfo($model->image->filePath)['extension'];
        //        $result = 'https://www.tvtshop.ru/yii2images/images/image-by-item-and-alias?item=Products' . $model->id . '&dirtyAlias=' . $model->image->urlAlias . '_300x.' . $format;
        //        ?>
        <!--        --><?//= LazyLoad::widget(['src' => $result]); ?>
        <?= LazyLoad::widget(['src' => $model->image->getUrl('300x')]); ?>
    </a>

    <?= $model->discountValue ?>

    <?= Fancybox::widget([
        "fancyboxClass" => "button_image",
        "fancyboxTitle" => "Посмотреть информацию о товаре, не покидая страницы",
        "fancyboxUrl" => Url::to(['products/view', 'product_alias' => $model->product_alias]),
        "fancyboxText" => 'Быстрый просмотр'
    ]) ?>

</div>

<div class="product-info">
    <div class="price">
        <?php $currentPrice = $model->currentPrice ?>
        <?= Html::tag('span', Yii::$app->formatter->asDecimal($currentPrice), ['class' => 'new-price']) ?>

        <?php
        if ($currentPrice !== $model->product_price) {
            echo Html::tag('del', Yii::$app->formatter->asDecimal($model->product_price), ['class' => 'old-price']);
        }
        ?>
    </div>

    <span class="category_product-info"><?= $model->productModel->category->name ?>,<br><?= $model->productModel->brand->brand_name ?></span>
</div>

<div class="actions-product">
    <?= Fancybox::widget([
        "fancyboxClass" => "cart-product-button",
        "fancyboxTitle" => "Добавить в корзину",
        "fancyboxUrl" => Url::to(['products/product-size-list', 'product_alias' => $model->product_alias]),
    ]) ?>

    <?= Html::a('', 'javascript:void(0);',
        [
            "class" => $model->getFavoritesButtonClass($favoritesProducts),
            "title" => "Добавить в избранное",
        ]
    ); ?>

    <?= Fancybox::widget([
        "fancyboxClass" => "oneclick-product-button",
        "fancyboxTitle" => "Купить в один клик",
        "fancyboxUrl" => Url::to(['orders/create-order-one-click', 'product_alias' => $model->product_alias]),
    ]) ?>
</div>

<div class="hover-bottom-info">
    <div class="sizes_product-info-block">
        <span class="sizes_product-info">Размеры:
            <?php foreach ((new app\models\ProductSizes)->getArrayProductSizes($model->productSizes) as $productSize) { ?>
                <?= $productSize->size_name ?>
            <?php } ?>
        </span>
    </div>
</div>


