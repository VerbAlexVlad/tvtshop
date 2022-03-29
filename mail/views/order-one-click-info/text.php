<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $orderModel app\models\Orders */
/* @var $productsModel app\models\ProductsSearch */
?>
Уважаемый пользователь!

Вам поступил заказ, с использованием кнопки "Купить в 1 клик"

- Артикул товара: <?= $productsModel->id ?>;
- Товар: <?= Html::a('Продукт. Арт. ' . $productsModel->id, 'www.tvtshop.ru' . Url::to(['/product/view', 'product_alias' => $productsModel->product_alias])) ?>
- Контакты покупателя: <?= $orderModel->order_phone ?>.

Просим в ближайшее время связаться с покупателем для уточнения деталей и подтверждения заказа.

С Уважением, команда "точьВточь".

Сообщение было отправлено автоматически. Пожалуйста не отвечайте на это сообщение.
