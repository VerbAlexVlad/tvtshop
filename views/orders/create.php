<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider */
/* @var $sizes_in_cart */
/* @var $model */

/**
 * СДЭК
 * https://github.com/cdek-it/sdk2.0
 * https://github.com/cdek-it/sdk2.0/blob/master/docs/index.md
 */
$client = new \Symfony\Component\HttpClient\Psr18Client();
$cdek = new \CdekSDK2\Client($client, 'EMscd6r9JnFiQ3bLoyjJY6eM78JrJceI', 'PjLZkKBHEiLK3YsjtNrt3TGNG0ahs3kG');
try {
    $cdek->authorize();
    $cdek->getToken();
    $cdek->getExpire();
} catch (\CdekSDK2\Exceptions\AuthException $exception) {
    //Авторизация не выполнена, не верные account и secure
    echo $exception->getMessage();
}
//$res = $cdek->offices()->getFiltered(['country_code' => 'ru']);
//if ($res->isOk()) {
//    $pvzlist = $cdek->formatResponseList($res, \CdekSDK2\Dto\PickupPointList::class);
////    $pvzlist->items;
//}

$this->title = Yii::t('app', 'Оформление заказа');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title container">
    <h1 class="h1_title"><?= Html::encode($this->title) ?></h1> <span
        class="number_title"><?= Yii::$app->inflection->pluralize($dataProvider ? $dataProvider->getTotalCount() : 0, 'товар') ?></span>
</div>

<?= $this->render('_form', [
    'model' => $model,
    'sizes_in_cart' => $sizes_in_cart,
    'dataProvider' => $dataProvider,
    'calcInfo' => $calcInfo,
]) ?>





