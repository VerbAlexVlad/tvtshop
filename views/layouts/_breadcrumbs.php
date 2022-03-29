<?php
use app\widgets\Alert;
use yii\widgets\Breadcrumbs;
?>

<div class="breadcrumbs">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>

</div>
<div class="container">
    <?= Alert::widget() ?>
</div>
