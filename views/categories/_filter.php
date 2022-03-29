<?php

use app\widgets\Fancybox;
use app\models\Categories;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="filter">
    <?php
    if (Yii::$app->request->get('category_alias')) {
        $category_name = "Категория: " . Categories::findOne(['alias' => Yii::$app->request->get('category_alias')])->name;
        $category_style = "filter-item black_filter-item";
    } elseif(Yii::$app->request->get('categories')) {
        $category_name = "Категории: " . count(Yii::$app->request->get('categories'));
        $category_style = "filter-item black_filter-item";
    } else {
        $category_name = "Категории";
        $category_style = "filter-item";
    }
    ?>
    <?= Fancybox::widget([
        "fancyboxClass" => $category_style,
        "fancyboxTitle" => "Показать товары для ...",
        "fancyboxUrl" => Url::to(array_filter(array_merge([
            'categories/filter-categories',
        ], Yii::$app->request->get()))),
        "fancyboxText" => $category_name
    ]) ?>


    <?php
    if (Yii::$app->request->get('sort')) {
        switch (Yii::$app->request->get('sort')) {
            case 'id':
                $sort_name = "Сначала старые";
                break;
            case '-id':
                $sort_name = "Сначала новые";
                break;
            case 'product_price':
                $sort_name = "Сначала дешевле";
                break;
            case '-product_price':
                $sort_name = "Сначала дороже";
                break;
            case 'views':
                $sort_name = "Сначала популярные";
                break;
            case '-views':
                $sort_name = "Сначала менее популярные";
                break;
        }

        $sort_style = "filter-item black_filter-item";
    } else {
        $sort_name = "Сортировка";
        $sort_style = "filter-item";
    }
    ?>

    <?= Fancybox::widget([
        "fancyboxClass" => $sort_style,
        "fancyboxTitle" => "Сортировать товары по ...",
        "fancyboxUrl" => Url::to(array_filter(array_merge([
            'categories/filter-sort',
        ], Yii::$app->request->get()))),
        "fancyboxText" => $sort_name
    ]) ?>

    <?php
    if (Yii::$app->request->get('all')) {
        switch (Yii::$app->request->get('all')) {
            case 'man':
                $floor_name = "Мужские товары";
                break;
            case 'woman':
                $floor_name = "Женские товары";
                break;
            case 'me':
                $floor_name = "Товары <b>моих</b> размеров";
                break;
            default:
                $floor_name = "Мужские и женские товары";
        }

        $floor_style = "filter-item black_filter-item";
    } else {
        $floor_name = "Пол";
        $floor_style = "filter-item";
    }
    ?>
    <?= Fancybox::widget([
        "fancyboxClass" => $floor_style,
        "fancyboxTitle" => "Показать товары для ...",
        "fancyboxUrl" => Url::to(array_filter(array_merge([
            'categories/filter-floor',
        ], Yii::$app->request->get()))),
        "fancyboxText" => $floor_name
    ]) ?>

    <?php if (Yii::$app->request->get('colors')) {
        $color_style = "filter-item black_filter-item";
        $color_name = "Цвет: " . count(array_filter(Yii::$app->request->get('colors')));
    } else {
        $color_style = "filter-item";
        $color_name = "Цвет";
    } ?>

    <?= Fancybox::widget([
        "fancyboxClass" => $color_style,
        "fancyboxTitle" => "Указать цвет",
        "fancyboxUrl" => Url::to(array_filter(array_merge([
            'categories/filter-color',
        ], Yii::$app->request->get()))),
        "fancyboxText" => $color_name
    ]) ?>

    <?php if (Yii::$app->request->get('sizes')) {
        $size_style = "filter-item black_filter-item";
        $size_name = "Размер: " . count(array_filter(Yii::$app->request->get('sizes')));
    } else {
        $size_style = "filter-item";
        $size_name = "Размер";
    } ?>

    <?= Fancybox::widget([
        "fancyboxClass" => $size_style,
        "fancyboxTitle" => "Указать размер",
        "fancyboxUrl" => Url::to(array_filter(array_merge([
            'categories/filter-size',
        ], Yii::$app->request->get()))),
        "fancyboxText" => $size_name
    ]) ?>

    <?php if (Yii::$app->request->get('brands')) {
        $brand_style = "filter-item black_filter-item";
        $brand_name = "Бренд: " . count(array_filter(Yii::$app->request->get('brands')));
    } else {
        $brand_style = "filter-item";
        $brand_name = "Бренд";
    } ?>

    <?= Fancybox::widget([
        "fancyboxClass" => $brand_style,
        "fancyboxTitle" => "Указать бренд",
        "fancyboxUrl" => Url::to(array_filter(array_merge([
            'categories/filter-brand',
        ], Yii::$app->request->get()))),
        "fancyboxText" => $brand_name
    ]) ?>

    <?php

    $min_price = Yii::$app->request->get('product_price_from');
    $max_price = Yii::$app->request->get('product_price_to');

    if ($min_price && $max_price) {
        $price_style = "filter-item black_filter-item";
        $price_name = "Цена: от {$min_price} до {$max_price} руб.";
    } elseif ($min_price) {
        $price_style = "filter-item black_filter-item";
        $price_name = "Цена: от {$min_price} руб.";
    } elseif ($max_price) {
        $price_style = "filter-item black_filter-item";
        $price_name = "Цена: до {$max_price} руб.";
    } else {
        $price_style = "filter-item";
        $price_name = "Цена";
    }
    ?>

    <?= Fancybox::widget([
        "fancyboxClass" => $price_style,
        "fancyboxTitle" => "Указать диапозон цен",
        "fancyboxUrl" => Url::to(array_filter(array_merge([
            'categories/filter-price',
        ], Yii::$app->request->get()))),
        "fancyboxText" => $price_name
    ]) ?>


    <?php if (Yii::$app->request->get('seasons')) {
        $season_style = "filter-item black_filter-item";
        $season_name = "Сезон: " . count(array_filter(Yii::$app->request->get('seasons')));
    } else {
        $season_style = "filter-item";
        $season_name = "Сезон";
    } ?>

    <?= Fancybox::widget([
        "fancyboxClass" => $season_style,
        "fancyboxTitle" => "Указать сезон",
        "fancyboxUrl" => Url::to(array_filter(array_merge([
            'categories/filter-season',
        ], Yii::$app->request->get()))),
        "fancyboxText" => $season_name
    ]) ?>

    <?= Html::a('Очистить всё', Url::to(array_filter([
        'categories/view',
        'category_alias' => Yii::$app->request->get('category_alias'),
        'all' => Yii::$app->request->get('all') ?? 'all'
    ])),
        [
            "class" => 'filter-cancel-item',
            "title" => 'Очистить фильтр',
        ]
    ); ?>
</div>

