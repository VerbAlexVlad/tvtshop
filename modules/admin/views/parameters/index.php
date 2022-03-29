<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Parameters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameters-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Parameters'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'width:auto'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'image',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width: 50px;'],
                'value' => function ($data) {
                    return Html::img($data->getImage()->getUrl('150x'), ['style' => 'width:100%']);
                }
            ],
            'parameter_name',
            'parameter_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
