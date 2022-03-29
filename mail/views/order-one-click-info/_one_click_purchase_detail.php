<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $orderModel app\models\Orders */
/* @var $productsModel app\models\ProductsSearch */
?>
<?php
$table_style = 'font-family:Arial, Helvetica, sans-serif;
            width: 100%;
            text-shadow: 1px 1px 0px #fff;
            background:#eaebec;
            margin-bottom:20px;
            border:#ccc 1px solid;
            border-collapse:separate;
            -moz-border-radius:3px;
            -webkit-border-radius:3px;
            border-radius:3px;
            -moz-box-shadow: 0 1px 2px #d1d1d1;
            -webkit-box-shadow: 0 1px 2px #d1d1d1;
            box-shadow: 0 1px 2px #d1d1d1;
            border-spacing: 0px;';

$table_th_style = 'font-weight:bold;
            padding:21px 25px 22px 25px;
            border-top:1px solid #fafafa;
            border-bottom:1px solid #e0e0e0;
            background: #ededed;
            background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
            background: -moz-linear-gradient(top,  #ededed,  #ebebeb);';

$table_tr_style = 'text-align: center;
            padding-left:20px;';

$table_td_style = 'padding:10px 5px;
            border-top: 1px solid #ffffff;
            border-bottom:1px solid #e0e0e0;
            border-left: 1px solid #e0e0e0;
            background: #fafafa;
            background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
            background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);';
?>

<style>
    label > input{ /* HIDE RADIO */
        visibility: hidden; /* Makes input not-clickable */
        position: absolute; /* Remove input from document flow */
    }
    label > input + img{ /* IMAGE STYLES */
        cursor:pointer;
        border:2px solid transparent;
    }
    label > input:checked + img{ /* (RADIO CHECKED) IMAGE STYLES */
        border:2px solid #f00;
    }
</style>

<table style="<?= $table_style ?>">
    <caption style="padding-bottom: 15px"><?= Html::tag('span','<b>Детали заказа:</b>'); ?></caption>

    <tr style="<?= $table_tr_style ?>">
        <th style="<?= $table_th_style ?>">Артикул товара</th>
        <th style="<?= $table_th_style ?>">Товары</th>
        <th style="<?= $table_th_style ?>">Контакты покупателя</th>
    </tr>

    <tr style="<?= $table_tr_style ?>">
        <td style="<?= $table_td_style ?>"><?= $productsModel->id ?></td>
        <td style="<?= $table_td_style ?>">
            <?= Html::a('Продукт. Арт. ' . $productsModel->id, Yii::$app->request->hostInfo . Url::to(['/product/view', 'product_alias' => $productsModel->product_alias])) ?>
        </td>
        <td style="<?= $table_td_style ?>"><?= $orderModel->order_phone ?></td>
    </tr>
</table>
