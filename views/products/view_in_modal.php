<?php

use yii\helpers\Html;
use yii\helpers\Url;use app\assets\ProductAppAsset;

ProductAppAsset::register($this);

$this->title = $model->productH1;

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
$this->params['breadcrumbs'][] = $this->title;

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

<div>
    <div class="title container">
        <h1 class="h1_title"><?= $this->title ?></h1>
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
                                Html::img(
                                    $imgUrlSmall,
                                    [
                                        'srcset' => "{$imgUrlSmall} 2x"
                                    ]
                                ),
                                $imgUrlBig,
                                [
                                    'data-zoom-id' => "Zoom-1",
                                    'data-image' => $imgUrlBig,
                                    'data-zoom-image-2x' => $imgUrlBig,
                                    'data-image-2x' => $imgUrlBig
                                ]
                            );
                        } ?>
                    </div>
                </div>
                <div class="main-selectors">
                    <?= Html::a(
                        Html::img(
                            $mainImg,
                            [
                                'srcset' => "{$mainImg} 2x"
                            ]
                        ),
                        $mainImg,
                        [
                            'class' => "MagicZoom",
                            'id' => "Zoom-1",
                            'data-zoom-image-2x' => $mainImg,
                            'data-image-2x' => $mainImg
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
                </ul>

                <!-- Контент -->
                <div class="tabs-items">
                    <div class="tabs-item" id="tab-1">
                        <h2 class="">Описание</h2>
                        <?php if (isset_and_not_empty($model->productDescription)) { ?>
                            <?= htmlspecialchars_decode($model->productDescription->description_text) ?>
                        <?php } else { ?>
                            Описание данного товара отсутствует
                        <?php } ?>
                    </div>
                    <div class="tabs-item" id="tab-2">
                        <h2 class="">Доставка</h2>
                        <ul class="push">
                            <li class="delivery-info" data-id="12">
                                <span class="x-pseudo-link">Почта России</span>
                            </li>
                            <li class="delivery-info" data-id="13">
                                <span class="x-pseudo-link">EMS</span>
                            </li>
                            <li class="delivery-info" data-id="14">
                                <span class="x-pseudo-link">Транспортная компания СДЭК</span>
                            </li>
                        </ul>
                    </div>
                    <div class="tabs-item" id="tab-3">
                        <h2 class="">Оплата</h2>
                        <ul class="push">
                            <li class="payment-info" data-id="7">
                                <span class="x-pseudo-link">На карту "Сбербанк"</span>
                            </li>
                            <li class="payment-info" data-id="8">
                                <span class="x-pseudo-link">На "Яндекс.Деньги"</span>
                            </li>
                            <li class="payment-info" data-id="16">
                                <span class="x-pseudo-link">На "QIWI Кошелёк"</span>
                            </li>
                        </ul>
                    </div>
                    <div class="tabs-item" id="tab-4">
                        <h2 class="">Обмен/возврат</h2>
                        <ul>
                            <li>Если модель не соответствует фото на сайте, модель пришла с браком или не соответствует
                                размеру - мы
                                делаем вам обмен на наш счет, т.е за пересылку товара вы не платите
                            </li>
                            <li>Если вам не понравилась модель или Вы выбрали не тот размер - доставка осуществляется ЗА ВАШ
                                СЧЕТ
                            </li>
                            <li>Возврат денежных средств за заказ мы не осуществляем, только обмен.</li>
                        </ul>
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

                    <?= $this->render('_product-sizes', ['model' => $model]); ?>

                </div>
            </div>
        </main>
    </div>
</div>
