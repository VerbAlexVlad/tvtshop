<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FavoritesProducts */

$this->title = Yii::t('app', 'Create Favorites Products');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Favorites Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="favorites-products-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
