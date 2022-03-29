<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Избранные товары';

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="favorites-products-block">
    <div class="title container">
        <h1 class="h1_title"><?= $this->title ?></h1> <span
                class="number_title"><?= Yii::$app->inflection->pluralize($dataProvider ? $dataProvider->getTotalCount() : 0, 'товар') ?></span>
    </div>

    <main class="container flex">
        <div class="products-category">
            <?php
            if ($dataProvider) {
                echo $this->render('_product_list', ['dataProvider' => $dataProvider]);
            } else {
                echo \yii\helpers\Html::tag('span', 'Корзина пуста');
            }
            ?>
        </div>
    </main>
</div>