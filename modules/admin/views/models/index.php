<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\ModelsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Models');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="models-index">
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::begin(['id' => 'models-index-save_pjax', 'clientOptions' => ['method' => 'POST'], 'enablePushState' => false]);
    } ?>
    <h1 class="page-header text-center">
        <?= Html::encode($this->title) ?>
    </h1>

    <?php  (new app\modules\admin\widgets\Alert)->begin(); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Model'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'model_name',
            'category_id',
            'brand_id',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Actions'),
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id], ['title' => Yii::t('app', 'Полная информация')]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id], ['title' => Yii::t('app', 'Редактировать информацию')]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id],
                            [
                                'data-confirm' => "Вы уверены, что хотите удалить этот элемент?",
                                'data-method' => 'post',
                                'data-pjax' => 1,
                                'title' => Yii::t('app', 'Удалить запись')
                            ]
                        );
                    }
                ]
            ],
        ],
    ]); ?>

    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::end();
    } ?>
</div>
