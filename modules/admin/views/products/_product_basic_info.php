<?php
    use yii\helpers\Html;

    $view = Html::a(
        Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']),
        ['/admin/products/view', 'id' => $data->id],
        ['data-toggle' => "tooltip", 'data-original-title' => "Смотреть информацию о товаре"]
    );
    $update = Html::a(
        Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']),
        ['/admin/products/update', 'id' => $data->id],
        ['data-toggle' => "tooltip", 'data-original-title' => "Редактировать информацию о товаре"]
    );
    $delete = Html::a(
        Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']),
        ['/admin/products/delete', 'id' => $data->id],
        ['data-toggle' => "tooltip", 'data-original-title' => "Удалить товар", 'data-confirm' => "Вы действительно хотите удалить данный товар?", 'data-method' => "post"]
    );
    $global_url = Html::a(
        Html::tag('span', '', ['class' => 'glyphicon glyphicon-globe']),
        $data->id,
        ['data-toggle' => "tooltip", 'data-original-title' => "Перейти на страницу товара"]
    );
    $url = Html::a(
        Html::tag('span', '', ['class' => 'glyphicon glyphicon-bold']),
        ['products/view', 'id' => $data->id],
        ['data-toggle' => "tooltip", 'data-original-title' => "Перейти на страницу товара"]
    );
?>

<div style="margin-bottom: 5px">
    Арт. <?= $data->id ?>
</div>

<div style="margin-bottom: 5px; min-width: 100px;">
    <?= Html::img($data->image->getUrl('150x'), ['style' => 'width:100%; margin-bottom: 5px']) ?>
</div>

<div>
    <?= "{$view} {$update} {$delete} {$global_url} {$url}" ?>
</div>