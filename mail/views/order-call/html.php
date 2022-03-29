<?php
use yii\helpers\Html;

/* @var $orderCallModel app\models\OrderCall */
?>

<div style="background-color:#f3f3f3">
    <div style="margin-right: auto; margin-left: auto; max-width: 800px; font-family: 'PT Serif Caption', serif; background-color: white;">
        <div style="padding: 10px;">
            <?= Html::tag('span', 'Уважаемый пользователь!') // строка приветствия ?><br><br>
            <?= Html::tag('span',"Вам поступил запрос звонка со страницы:") ?><br><br>
            <?php try {
                $url = $orderCallModel->this_url;
                echo Html::a($url, $url);
            } catch (JsonException $e) {
                echo "Ошибка: " . $e->getMessage();
            } ?><br><br>

            <?= Html::tag('span',"- Имя: " . $orderCallModel->oc_user_name) ?><br>
            <?= Html::tag('span',"- Телефон: " . $orderCallModel->oc_user_phone) ?><br>
            <?= Html::tag('span',"- Коментарий: " . $orderCallModel->oc_question) ?><br><br>

            <?= Html::tag('span',"Просим в ближайшее время связаться с покупателем для уточнения деталей.") ?><br><br>

            <?= Html::tag('span','С Уважением, команда "точьВточь".') ?><br><br>

            <?= Html::tag('span','Сообщение было отправлено автоматически. Пожалуйста не отвечайте на это сообщение.') ?>
        </div>
    </div>
</div>