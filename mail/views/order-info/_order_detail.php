<?php

use app\models\Image;
use app\models\Products;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $order */
/* @var $order_items */
?>


<p style="color:#484a42;font-size:24px;font-weight:bold;line-height:30px;margin:0 30px 33px 30px;text-align:center;text-transform:uppercase">
    Информация о заказе
</p>

<table cellspacing="0"
       style="background:#eaebec;border:1px solid #ccc;border-collapse:separate;border-radius:3px;font-family:'arial' , 'helvetica' , sans-serif;margin-bottom:20px;width:100%">
    <tbody>
    <tr>
        <th style="width: 40px; background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:5px"></th>

        <th style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:5px">
            Информация о товаре
        </th>
        <th style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:5px">
            Сумма
        </th>
    </tr>
    <?php $i = 1 ?>
    <?php foreach ($order_items as $id => $item) {
        static $summ = 0 ?>
        <tr>
            <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:10px;text-align: center">
                <?= $i ?>.
            </td>
            <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:5px">

                <?php $urlAlias = Image::find()->where(['itemId' => $item->product_id])->andWhere(['modelName' => 'Products'])->limit(1)->one(); ?>
                <div style="width:125px;float: left">
                    <img src="https://www.tvtshop.ru/yii2images/images/image-by-item-and-alias?item=Products<?= $item->product_id ?>&dirtyAlias=<?= $urlAlias ? $urlAlias->urlAlias : null ?>_300x345.jpg"
                         alt="" style="width:100%" class="">
                </div>

                <div style="float:left; padding-top: 15px">
                    <ul>
                        <li>
                            <?php $product = Products::findOne($item->product_id) ?>
                            <?= Html::a($item->name, 'www.tvtshop.ru' . Url::to(['/product/view', 'product_alias' => $product->alias])) ?>
                        </li>
                        <li><strong>Артикул:</strong> <?= $item->product_id ?></li>
                        <li><strong>Размер:</strong> <?= $item->size_name ?></li>
                        <li><strong>Цена:</strong> <?= $item->price ?>&nbsp;руб.</li>
                        <?php $discount = 0; ?>
                        <?php if ($item->discount) { ?>
                            <?php
                            switch ($item->discount_unit) {
                                case 1:
                                    $discount_val = "%";
                                    $discount = $item->price * $item->discount / 100;
                                    break;
                                case 2:
                                    $discount_val = "руб.";
                                    $discount = $item->price - $item->discount;
                                    break;
                            }
                            ?>
                            <li><strong>Скидка:</strong> <?= $item->discount ?>&nbsp;<?= $discount_val ?></li>
                        <?php } ?>
                        <li><strong>Количество:</strong> <?= $item->qty_item ?>&nbsp;ед.</li>
                    </ul>
                </div>
            </td>

            <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:10px;text-align: center">
                <?= $item->sum_item - $discount * $item->qty_item ?>&nbsp;руб.
            </td>
        </tr>
        <?php $summ += $item->sum_item - $discount * $item->qty_item;
        $i++;
    } ?>


    <tr>
        <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:10px;text-align: center">
            <?= $i ?>
        </td>
        <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:5px">
            Доставка
        </td>

        <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:10px;text-align: center">
            <?= $order->price_delivery ?>&nbsp;руб.
        </td>
    </tr>

    <tr>
        <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:10px;text-align: center"></td>
        <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:10px;text-align: right">
            <strong>Итого:</strong>
        </td>

        <td style="background:#fafafa;border-bottom-color:#e0e0e0;border-bottom-style:solid;border-bottom-width:1px;border-left-color:#e0e0e0;border-left-style:solid;border-left-width:1px;border-top-color:#ffffff;border-top-style:solid;border-top-width:1px;padding:10px;text-align: center">
            <?= $summ + $order->price_delivery ?>&nbsp;руб.
        </td>
    </tr>
    </tbody>
</table>

