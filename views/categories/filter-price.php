<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<form action="<?= Url::to(array_filter(array_merge(['categories/view'], Yii::$app->request->get()))) ?>" method="get">
    <?php if (Yii::$app->request->get()) {
        foreach (array_filter(Yii::$app->request->get()) as $id => $get) {
            if (!in_array($id, ['product_price_from', 'product_price_to', 'category_alias', 'all'])) {
                if (is_array($get)) {
                    foreach ($get as $item) {
                        echo Html::input('text', $id . '[]', $item, ['hidden' => true]);
                    }
                } else {
                    echo Html::input('text', $id, $get, ['hidden' => true]);
                }
            }
        }
    } ?>

    <section class="filter-list">
        <span class="title_filter-price">Укажите диапозон цен</span>
        <div class="filter__block_filter-price">
            <span class="">От</span>
            <input type="number"
                   class="input_filter-search"
                   name="product_price_from"
                   value="<?= Yii::$app->request->get('product_price_from') ?>"
                   placeholder="<?= floor($dataProvider->query->min('product_price')) ?>"
                   min="0"
                   max="99999"
            />

            <span class="">До</span>
            <input type="number"
                   class="input_filter-search"
                   name="product_price_to"
                   value="<?= Yii::$app->request->get('product_price_to') ?>"
                   placeholder="<?= ceil($dataProvider->query->max('product_price')) ?>"
                   min="0"
                   max="99999"
            />
        </div>

        <div class="filter-button">
            <button class="btn">Применить</button>
        </div>
    </section>
</form>






