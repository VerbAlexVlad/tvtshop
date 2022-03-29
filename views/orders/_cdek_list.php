<?php

use yii\helpers\Html;

echo Html::label('Способы доставки', null, ['class' => 'control-label']);
echo Html::activeRadioList($model, 'order_delivery_id', [1, 2, 3], ['itemOptions' => ['required' => true]]);
?>


