<?php
use yii\widgets\ListView;
use rmrevin\yii\fontawesome\FAS;
/* @var $dataProvider yii\data\ActiveDataProvider */
try {
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['tag' => 'div', 'class' => 'product-list'],
        'itemOptions' => ['tag' => 'div', 'class' => 'product-item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_product_list_item', ['model' => $model]);
        },
        'layout' => "{items}{pager}",
        'pager' => [
            'firstPageLabel' => FAS::icon('angle-double-left'),
            'lastPageLabel' => FAS::icon('angle-double-right'),
            'nextPageLabel' => FAS::icon('angle-right'),
            'prevPageLabel' => FAS::icon('angle-left'),
            'maxButtonCount' => 8,
        ],
    ]);
} catch (Exception $e) {
}