<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $orderCallModel app\models\OrderCall */
?>
Уважаемый пользователь!

Вам поступил запрос звонка со страницы:
<?php try { ?>
    <?php $url = $orderCallModel->this_url; ?>
    <?= Html::a($url, $url) ?>
<?php } catch (JsonException $e) { ?>
    <?= "Ошибка: " . $e->getMessage() ?>
<?php } ?>

- Имя: <?= $orderCallModel->oc_user_name ?>;
- Телефон: <?= $orderCallModel->oc_user_phone ?>;
- Коментарий: <?= $orderCallModel->oc_question ?>.

Просим в ближайшее время связаться с покупателем для уточнения деталей.

С Уважением, команда "точьВточь".

Сообщение было отправлено автоматически. Пожалуйста не отвечайте на это сообщение.
