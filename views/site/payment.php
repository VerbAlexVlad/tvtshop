<?php

use yii\helpers\Html;
?>
<div class="container">
    <h1 class="text-center"><span>Информация об оплате</span></h1>
    <hr>
    <p class="text-center">Дорогой покупатель! В данной таблице приведены способы оплаты и их описания. Ознакомьтесь с ними внимательно перед покупкой.</p>
    <br>

    <table class="table table-bordered">
        <tr>
            <th class="text-center">Способ оплаты</th>
            <th class="text-center">Описание</th>
        </tr>
        <tr>
            <td><?= Html::img("@web/img/ico/yandex.jpg", ['alt' => 'Яндекс кошелек', 'style'=>'width:100%']) ?></td>
            <td>
                <p><strong>Яндекс деньги:</strong></p>
                <span>Номер кошелька, для осуществления оплаты, будет выслан по электронному адресу после подтверждения заказа.</span>
            </td>
        </tr>
        <tr>
            <td><?= Html::img("@web/img/ico/sberbank.jpg", ['alt' => 'Сбербанк', 'style'=>'width:100%']) ?></td>
            <td>
                <p><strong>Сбербанк:</strong></p>
                <span>Номер карты и привязанного к ней телефона, для осуществления оплаты, будет выслан по электронному адресу и по СМС после подтверждения заказа.</span>
            </td>
        </tr>
        <tr>
            <td><?= Html::img("@web/img/ico/qiwi.jpg", ['alt' => 'Киви', 'style'=>'width:100%']) ?></td>
            <td>
                <p><strong>Qiwi кошелек:</strong></p>
                <span>Номер кошелька, для осуществления оплаты, будет выслан по электронному адресу после подтверждения заказа.</span>
            </td>
        </tr>
    </table>
    <br>
    <p class="text-center">Заказ считается оформленным сразу после поступления оплаты или предоплаты.</p>
    <br>
</div>
