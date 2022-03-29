<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Категории товаров');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<div class="categories-index">
    <p>
        <?= Html::a(Yii::t('app', 'Добавить категорию'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'width:auto'],
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'image',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 50px;'],
                'value' => function ($data) {
                    return Html::img($data->getImage()->getUrl('150x'), ['style' => 'width:100%']);
                }
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) {
                    $depth = $data->depth * 4 - 4;

                    if ($data->depth == 1) {
                        return Html::tag('strong', str_repeat("&nbsp;", $depth < 0 ? 0 : $depth) . $data->name);
                    }

                    return str_repeat("&nbsp;", $depth < 0 ? 0 : $depth) . $data->name;
                }
            ],
            'name_singular_category',
            'alias',
            [
                'attribute' => 'parent_id',
                'value' => function ($data) {
                    return $data->parentForId->name ?? false;
                }
            ],
            //'description',
            //'categories_extended_description:ntext',
            //'keywords',
            //'floor',
            //'title',
            //'lft',
            //'rgt',
            //'depth',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
