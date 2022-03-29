<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Models */

$this->title = Yii::t('app', 'Create Models');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="models-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
