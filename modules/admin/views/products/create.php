<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Products */
/* @var $description_model app\modules\admin\models\Descriptions */

$this->title = Yii::t('app', 'Добавление товара');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Товары'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<div class="products-create">
    <?= $this->render('_form', [
        'model' => $model,
        'description_model' => $description_model,
    ]) ?>
</div>
