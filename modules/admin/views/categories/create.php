<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Categories */

$this->title = Yii::t('app', 'Create Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Добавление категории'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<div class="categories-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
