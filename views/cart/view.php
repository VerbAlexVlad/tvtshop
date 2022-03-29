<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $sizes_in_cart */
$this->title = 'Корзина покупателя';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-block">
    <div class="title container">
        <h1 class="h1_title"><?= $this->title ?></h1> <span
            class="number_title"><?= Yii::$app->inflection->pluralize($dataProvider ? $dataProvider->getTotalCount() : 0, 'товар') ?></span>
    </div>

    <main class="cart-product-block container">
        <div class="products-category">
            <?php
            if ($dataProvider) {
                echo $this->render('_product_list', ['dataProvider' => $dataProvider]);
            } else {
                echo Html::tag('span', 'Корзина пуста');
            }
            ?>
        </div>
        <div class="summary-information">
            <?php if ($dataProvider) { ?>
                <?php
                $price = 0;
                $currentPrice = 0;
                foreach ($dataProvider->getModels() as $item) {
                    if (!Yii::$app->user->isGuest) {
                        $value = $sizes_in_cart[$item->id]->qty;
                    } else {
                        $value = $_SESSION['sizes_in_cart'][$item->id]['qty'] ?? 1;
                    }
                    $price += $item->psProduct->product_price * $value;
                    $currentPrice += $item->psProduct->currentPrice * $value;
                }
                ?>
                <div class="sticky-summary-information">
                    <div class="summary item_summary-information">
                        <span>Итого</span>
                        <span class="product_discount"><?= $currentPrice ?></span>
                    </div>
                    <div class="item_summary-information">
                        <span>Цена без скидки</span>
                        <span class="product_price"><?= $price ?></span>
                    </div>
                    <div class="item_summary-information">
                        <span>Скидка</span>
                        <span class="summ-discount"><?= $price-$currentPrice ?></span>
                    </div>
                    <a href="<?= \yii\helpers\Url::to(['orders/create'])?>" class="product-cart-button product-cart-link">Оформить заказ</a>
                </div>
            <?php } ?>
        </div>
    </main>
</div>
