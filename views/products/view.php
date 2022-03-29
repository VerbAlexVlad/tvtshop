<?php

use mickey\commentator\extensions\comments_widget\CommentsWidget;
use mickey\commentator\helpers\CHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model */
/* @var $categoryParentsAndChildren */

$this->title = $model->productTitle;
$h1 = $model->productH1;

foreach ($categoryParentsAndChildren['parents'] as $parentCategory) {
    $this->params['breadcrumbs'][] = [
        'label' => $parentCategory['name'],
        'url' => Url::to(['categories/view', 'category_alias' => $parentCategory['alias']])
    ];
}

foreach ($categoryParentsAndChildren['childrens'] as $parentCategory) {
    $this->params['breadcrumbs'][] = [
        'label' => $parentCategory['name'],
        'url' => Url::to(['categories/view', 'category_alias' => $parentCategory['alias']])
    ];
}
$this->params['breadcrumbs'][] = $h1;

$js = <<< JS
    var mzOptions = {
        textHoverZoomHint: "",
        textClickZoomHint: "",
        zoomPosition: "inner",
        hint: "off",
        smoothing: false,
        lazyZoom: false,
        transitionEffect: false,
        selectorTrigger: "hover"
    };
JS;
$this->registerJs($js, $position = yii\web\View::POS_END, $key = null);
$gallery = $model->images;
$manyImg = $model->image;
?>
<div class="product-page">
    <div class="title container">
        <h1 class="h1_title"><?= $h1 ?></h1>
    </div>

    <div class="product-block product-key container flex" data-key="<?= $model->id ?>">
        <main class="product-main">
            <div class="images_product">
                <div class="selector">
                    <div class="selectors">
                        <?php foreach ($gallery as $item) {
                            $imgUrlBig = $item->getUrl('x700');
                            $imgUrlSmall = $item->getUrl('x100');
                            if ($item->isMain == 1) $mainImg = $imgUrlBig;

                            echo Html::a(
                                Html::img($imgUrlSmall),
                                $imgUrlBig,
                                [
                                    'data-zoom-id' => "Zoom-1",
                                    'data-image' => $imgUrlBig,
                                ]
                            );
                        } ?>
                    </div>
                </div>
                <div class="main-selectors">
                    <?= Html::a(
                        Html::img(
                            $mainImg
                        ),
                        $mainImg,
                        [
                            'class' => "MagicZoom",
                            'id' => "Zoom-1",
                        ]
                    );
                    ?>
                </div>
            </div>
            <div class="product-aside" id="tabs">
                <!-- Кнопки -->
                <ul class="tabs-nav">
                    <li><a href="#tab-1">Описание</a></li>
                    <li><a href="#tab-2">Доставка</a></li>
                    <li><a href="#tab-3">Оплата</a></li>
                    <li><a href="#tab-4">Обмен/возврат</a></li>
                    <li><a href="#tab-5">Отзывы (<?= CHelper::getCommentsCount($model->productModel->id); ?>)</a></li>
                </ul>

                <!-- Контент -->
                <div class="tabs-items">
                    <div class="tabs-item" id="tab-1">
                        <?php if (isset_and_not_empty($model->productDescription)) { ?>
                            <?= htmlspecialchars_decode($model->productDescription->description_text) ?>
                        <?php } else { ?>
                            Описание данного товара отсутствует
                        <?php } ?>
                    </div>
                    <div class="tabs-item" id="tab-2">
                        <ul class="">
                            <?php foreach ($model->productShop->deliveries as $deliverie) { ?>
                                <li class="">
                                    <h3 class=""><?= $deliverie->delivery_name ?></h3>
                                    <span class=""><?= $deliverie->delivery_description ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="tabs-item" id="tab-3">
                        <ul class="">
                            <?php foreach ($model->productShop->payments as $payments) { ?>
                                <li class="">
                                    <h3 class=""><?= $payments->payment_name ?></h3>
                                    <span class=""><?= $payments->payment_description ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="tabs-item" id="tab-4">
                        <ul>
                            <li>
                                Если модель не соответствует фото на сайте, модель пришла с браком или не соответствует
                                размеру - мы делаем вам обмен на наш счет, т.е за пересылку товара вы не платите
                            </li>
                            <li>
                                Если вам не понравилась модель или Вы выбрали не тот размер - доставка осуществляется ЗА
                                ВАШ
                                СЧЕТ
                            </li>
                            <li>
                                Возврат денежных средств за заказ мы не осуществляем, только обмен.
                            </li>
                        </ul>
                    </div>
                    <div class="tabs-item" id="tab-5">

                        <?= CommentsWidget::widget([
                            // Необязательный параметр, включает поддержку микроразметки, по-умолчанию: false
                            'enableMicrodata' => true,
                            'model_id' => $model->productModel->id,
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="info_product-block">
                <div class="parametres">
                    <h2 class="h2_parametres">Основные параметры</h2>
                    <ul class="parametres-list">
                        <li class="parametres-item">
                            <span class="name_parametres-item">Артикул</span>
                            <span class="val_parametres-item"><?= $model->id ?></span>
                        </li>
                        <li class="parametres-item">
                            <span class="name_parametres-item">Бренд</span>
                            <a href="/brand-adidas" title="Перейти на страницу бренда"
                               class="val_parametres-item"><?= $model->productModel->brand->brand_name ?></a>
                        </li>
                        <li class="parametres-item">
                            <span class="name_parametres-item">Модель</span>
                            <span class="val_parametres-item"><?= $model->productModel->model_name ?></span>
                        </li>
                        <li class="parametres-item">
                            <span class="name_parametres-item">Категория</span>
                            <a href="/brand-adidas" title="Показать все товары категории"
                               class="val_parametres-item"><?= $model->productModel->category->name ?></a>
                        </li>
                        <li class="parametres-item">
                            <span class="name_parametres-item">Пол</span>
                            <span class="val_parametres-item"><?= $model->floor->floor_name ?></span>
                        </li>
                        <li class="parametres-item">
                            <span class="name_parametres-item">Цвет</span>
                            <span class="val_parametres-item"><?= $model->implodeColors ?></span>
                        </li>
                        <li class="parametres-item">
                            <span class="name_parametres-item">Сезон</span>
                            <span class="val_parametres-item"><?= $model->productsSeason->season_name ?></span>
                        </li>
                        <li class="parametres-item">
                            <span class="name_parametres-item">Наличие</span>
                            <span class="val_parametres-item"><?= $model->productSvailability ?></span>
                        </li>
                    </ul>

                    <div class="product-price">
                        <?php $currentPrice = $model->currentPrice ?>
                        <?= Html::tag('span', Yii::$app->formatter->asDecimal($currentPrice), ['class' => 'product-new-price']) ?>

                        <?php
                        if ($currentPrice !== $model->product_price) {
                            echo Html::tag('del', Yii::$app->formatter->asDecimal($model->product_price), ['class' => 'product-old-price']);
                        }
                        ?>
                    </div>

                    <?= $this->render('_product-sizes', ['model' => $model, 'oneClickForm' => false]); ?>

                </div>
            </div>
            <div class="comment_product-block">
                <h2 class="h2_parametres">Похожие товары</h2>

            </div>
        </main>


    </div>
</div>