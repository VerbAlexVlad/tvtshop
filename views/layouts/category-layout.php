<?php

use andrewdanilov\fancybox\FancyboxAsset;
use app\assets\CategoryAppAsset;

use yii\helpers\Html;

\yii\web\JqueryAsset::register($this);
FancyboxAsset::register($this);
CategoryAppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="body">
<?php $this->beginBody() ?>
<div>
    <?= $this->render('_header'); ?>

    <?= $this->render('_breadcrumbs'); ?>

    <?= $content ?>

    <?= $this->render('_footer'); ?>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
