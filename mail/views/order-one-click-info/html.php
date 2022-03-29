<?php
use yii\helpers\Html;

/* @var $orderModel app\models\Orders */
/* @var $productsModel app\models\ProductsSearch */
?>

<div style="background-color:#f3f3f3">
    <div style="border: 1px solid #40403e; margin-right: auto; margin-left: auto; max-width: 800px; font-family: 'PT Serif Caption', serif; background-color: white;">
        <div style="padding: 10px;">
            <?= Html::tag('span', 'Уважаемый пользователь!') // строка приветствия ?><br><br>
            <?= Html::tag('span',"Вам поступил заказ, с использованием кнопки \"Купить в 1 клик\"") ?><br><br>

            <?= $this->render('_one_click_purchase_detail', ['orderModel' => $orderModel, 'productsModel' => $productsModel]) ?>

            <?= Html::tag('span',"Просим в ближайшее время связаться с покупателем для уточнения деталей и подтверждения заказа.") ?><br><br>

            <?= Html::tag('span','С Уважением, команда "точьВточь".') ?><br><br>

            <?= Html::tag('span','Сообщение было отправлено автоматически. Пожалуйста не отвечайте на это сообщение') ?>
        </div>
    </div>
</div>