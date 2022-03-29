<?php

use app\modules\admin\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Discounts */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Discounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="discounts-view">
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::begin(['id' => 'discounts-view-save_pjax', 'enablePushState' => false]);
    } ?>
    <h1 class="page-header text-center">
        <?= Html::encode($this->title) ?>
    </h1>

    <?php  (new app\modules\admin\widgets\Alert)->begin(); ?>

    <?=  (new app\modules\admin\widgets\UpdateAndDelete())->begin($model); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'discount_name',
            'discount_value',
            'discount_unit_id',
            'discount_from_date',
            'discount_to_date',
        ],
    ]) ?>
    <?php if (Yii::$app->request->isAjax) {
        \yii\widgets\Pjax::end();
    } ?>
</div>
