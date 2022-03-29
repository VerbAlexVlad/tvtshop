<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Products */
/* @var $description_model app\modules\admin\models\Descriptions */

$this->title = Yii::t('app', 'Редактирование товара: арт. {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="products-update">
    <?= $this->render('_form', [
        'model' => $model,
        'description_model' => $description_model,
    ]) ?>
</div>
