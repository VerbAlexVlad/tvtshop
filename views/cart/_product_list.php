<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use rmrevin\yii\fontawesome\FAS;
?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['tag' => 'div', 'class' => 'product-list'],
    'itemOptions' => ['tag' => 'div', 'class' => 'product-item'],
    'itemView' => function ($model, $key, $index, $widget) {
        return $this->render('_product_list_item', ['model' => $model]);

        // or just do some echo
        // return $model->title . ' posted by ' . $model->author;
    },
    'layout' => "{items}",
    'pager' => [
        'firstPageLabel' => FAS::icon('angle-double-left'),
        'lastPageLabel' => FAS::icon('angle-double-right'),
        'nextPageLabel' => FAS::icon('angle-right'),
        'prevPageLabel' => FAS::icon('angle-left'),
        'maxButtonCount' => 8,
    ],
]) ?>