<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DeletedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список фильмов';

?>
<div class="container">
    <!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
    <!---->
    <!--    <p>-->
    <!--        --><?//= Html::a(Yii::t('app', '+ Добавить фильм'), ['create'], ['class' => 'btn btn-success']) ?>
    <!--    </p>-->

    <section class="film-list" style="text-align: center">
        <?php foreach ($dataProvider->getModels() as $id=>$item) { ?>
            <section class="film-item border border-primary">
                <h2><?= $item->id ?>. <?= $item->name ?></h2>
                <img src="<?= $item->url ?>">
                <p><?= $item->desc ?></p>
                <div>
                    <button class="btn vatch-button btn-default <?= $item->votch ? "btn-success" : "" ?>" data-id="<?=$item->id?>">Смотрела</button>
                    <button class="btn good-button btn-default <?= $item->good ? "btn-success" : "" ?>" data-id="<?=$item->id?>">Можно посмотреть</button>
                    <button class="btn bad-button btn-default <?= $item->bad ? "btn-danger" : "" ?>" data-id="<?=$item->id?>">Точно нет!!!</button>
                </div>
            </section>
        <?php } ?>
    </section>
    <style>
        .film-item{
            border-bottom: 1px solid #eee;
            padding-bottom: 25px;
            margin-bottom: 25px;
        }
        .film-item img{
            width: 100%;
            max-width: 350px;
        }
        .film-item img, .film-item p{
            margin-bottom: 15px;
        }
    </style>
</div>
