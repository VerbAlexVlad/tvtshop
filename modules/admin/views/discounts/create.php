<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Discounts */

$this->title = Yii::t('app', 'Create Discounts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Discounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discounts-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
