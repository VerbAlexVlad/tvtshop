<?php

use yii\helpers\Html;

?>

<?= Html::tag('span', 'Уважаемый пользователь!'); // строка приветствия  ?><br><br>

<?= Html::tag('span', 'Данное письмо сгенерировано в ответ на запрос на изменение пароля для учетной записи ' . $user->email) ?>

<div style="width:100%; text-align: center; margin: 2em 0;">
    <?= Html::tag('span', '<b>Для ввода нового пароля, пожалуйста перейдите по ссылке:</b>'); // ссылка с ключом, перейдя по которой пользователь перейдет в действие ResetPassword контроллера Main и через $_GET передаст секретный ключ $key  ?>
    <br>

    <?= Html::a(Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/site/resetpassword',
            'key' => $user->secret_key
        ]),
        Yii::$app->urlManager->createAbsoluteUrl(
            [
                '/site/resetpassword',
                'key' => $user->secret_key
            ])); // ссылка с ключом, перейдя по которой пользователь перейдет в действие ResetPassword контроллера Main и через $_GET передаст секретный ключ $key
    ?>
</div>

<hr>
<?= Html::tag('span', 'Обратите внимание.'); // ссылка с ключом, перейдя по которой пользователь перейдет в действие ResetPassword контроллера Main и через $_GET передаст секретный ключ $key  ?>

<ol>
    <?= Html::tag('li', 'Если пароль так и не будет изменен, для доступа к системе будет использоваться нынешний пароль.') ?>
    <?= Html::tag('li', 'Ссылка, для смены пароля, действительна только один раз. Срок жизни данной ссылки равен 24 часам с момента отправки письма.') ?>
    <?= Html::tag('li', 'Вы так же можете воспользоваться ссылкой "Забыли пароль?" на странице входа в аккаунт, для повторного запроса. В данном случае, ссылка полученная в этом письме, становится недействительной.') ?>
    <?= Html::tag('li', 'Письмо не подлежит пересылке третьим лицам. Храните это письмо в надежном месте, недоступном посторонним.') ?>
</ol>
<hr>

<?= Html::tag('span', 'Если Вы не запрашивали изменение пароля на сайте "точьВточь", просто <b>проигнорируйте</b> данное письмо. Возможно кто-то совершил ошибку.'); // ссылка с ключом, перейдя по которой пользователь перейдет в действие ResetPassword контроллера Main и через $_GET передаст секретный ключ $key  ?>
<br><br>

<?= Html::tag('span', 'С Уважением, команда "точьВточь".'); // ссылка с ключом, перейдя по которой пользователь перейдет в действие ResetPassword контроллера Main и через $_GET передаст секретный ключ $key  ?>
<br><br>

<?= Html::tag('span', 'Сообщение было отправлено автоматически. Пожалуйста не отвечайте на это сообщение'); // ссылка с ключом, перейдя по которой пользователь перейдет в действие ResetPassword контроллера Main и через $_GET передаст секретный ключ $key  ?>
