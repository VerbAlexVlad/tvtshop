<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Deleted */

$this->title = Yii::t('app', 'Create Deleted');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deleteds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deleted-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
