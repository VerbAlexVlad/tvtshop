<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div style="margin-right: auto; margin-left: auto; max-width: 600px; font-family: 'PT Serif Caption', serif;">
    <div style="text-align: end;margin-bottom: 15px;text-align: end;">
        <a style="color:#000000;font-family:'arial' , serif;line-height:inherit;margin:0;padding:0;text-decoration:none" href="tel:89953288200">
            +7&nbsp;(964)&nbsp;236&nbsp;99&#8209;88
        </a>
    </div>
    <div style="height: 65px;margin-bottom: 15px;text-align:center;width:100%">
        <a style="text-decoration: none;"
           href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/']) ?>">
            <img src="<?= $message->embed(Yii::getAlias('@app/web/img/logo.png')) ?>" alt="">
        </a>
    </div>
    <div style="font-family:'arial', serif;line-height:inherit;margin:0;padding: 10px;background-color: #eee;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%"
               style="border-collapse:collapse;font-family:'arial',serif;line-height:inherit;margin:0;padding:0;vertical-align:top">
            <tbody style="font-family:'arial',serif;line-height:inherit;margin:0;padding:0">
            <tr style="border-collapse:collapse;font-family:'arial',serif;line-height:inherit;margin:0;padding:0;vertical-align:top">
                <td align="left" height="34" width="200"
                    style="border-collapse:collapse;font-family:'arial',serif;line-height:inherit;margin:0;padding:0;vertical-align:middle !important">
                    <div style="font-family:'arial',serif;line-height:normal;margin:0;padding:0">
                        <?php $link = Html::a('Мужские товары', Yii::$app->request->hostInfo . Url::to(['categories/view', 'all' => 'man']), [
                            'target' => "_blank",
                            'style' => "color:#000000;font-family:'arial',serif;line-height:inherit;margin:0;padding:0;text-decoration:none",
                            'rel' => "noopener noreferrer"
                        ]); ?>

                        <?= Html::tag('span', $link, ['style' => "color:#000000; font-family:'tahoma', 'arial', 'helvetica', sans-serif; font-size:14px; line-height:16px; margin:0; padding:0; text-transform:uppercase"]) ?>
                    </div>
                </td>
                <td align="left" height="34" width="200"
                    style="border-collapse:collapse;font-family:'arial',serif;line-height:inherit;margin:0;padding:0;vertical-align:middle !important">
                    <div style="font-family:'arial',serif;line-height:normal;margin:0;padding:0">
                        <span style="color:#000000;font-family:'tahoma' , 'arial' , 'helvetica' , sans-serif;font-size:14px;line-height:16px;margin:0;padding:0;text-transform:uppercase">
                            <a
                                    href="<?= Yii::$app->request->hostInfo . Url::to(['categories/view', 'all' => 'woman']) ?>"
                                    target="_blank"
                                    style="color:#000000;font-family:'arial',serif;line-height:inherit;margin:0;padding:0;text-decoration:none"
                                    rel="noopener noreferrer">Жениские товары
                            </a>
                        </span>
                    </div>
                </td>
                <td align="left" height="34" width="200"
                    style="border-collapse:collapse;font-family:'arial',serif;line-height:inherit;margin:0;padding:0;vertical-align:middle !important">
                    <div style="font-family:'arial',serif;line-height:normal;margin:0;padding:0"><span
                                style="color:#000000;font-family:'tahoma' , 'arial' , 'helvetica' , sans-serif;font-size:14px;line-height:16px;margin:0;padding:0;text-transform:uppercase"> <a
                                    href="<?= Yii::$app->request->hostInfo . Url::to(['userparams/update']) ?>"
                                    target="_blank"
                                    style="color:#000000;font-family:'arial',serif;line-height:inherit;margin:0;padding:0;text-decoration:none"
                                    rel="noopener noreferrer">Товары моих размеров</a> </span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
