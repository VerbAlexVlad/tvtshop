<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use rmrevin\yii\fontawesome\FAS;

$script = <<< JS
// let ias = new InfiniteAjaxScroll('.product-list', {
//   item: '.product-item',
//   next: '.next',
//   pagination: '.pagination',
//   responseType: 'json',
//   scrollContainer: '.product-list'
// });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_LOAD);

$hidden = false;
$page = $_GET['page'] ?? 1;

if($dataProvider->getTotalCount() / $dataProvider->getPagination()->pageSize < $page) {
    $hidden = true;
}

$showMore = Html::tag(
    'div',
    Html::buttonInput(
        'Показать ещё',
        [
            'class' => 'show-more_products btn',
            'data-category-alias' => $_GET['category_alias'] ?? false,
            'data-all' => $_GET['all'] ?? false
        ]
    ),
    ['class' => 'show-more', 'hidden' => $hidden]
);


echo ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['tag' => 'div', 'class' => 'product-list'],
    'itemOptions' => ['tag' => 'div', 'class' => 'product-item product-key'],
    'itemView' => function ($model, $key, $index, $widget) use ($favoritesProducts) {
        return $this->render('_product_list_item', [
            'model' => $model,
            'favoritesProducts' => $favoritesProducts,
        ]);

        // or just do some echo
        // return $model->title . ' posted by ' . $model->author;
    },
    'layout' => "{items}<div class='navigation-block_product-list'>{$showMore}{pager}</div>",
    'pager' => [
        'firstPageLabel' => FAS::icon('angle-double-left'),
        'lastPageLabel' => FAS::icon('angle-double-right'),
        'nextPageLabel' => FAS::icon('angle-right'),
        'prevPageLabel' => FAS::icon('angle-left'),
        'maxButtonCount' => 8,
    ],
]);