<?php

use yii\helpers\Html;
use yii\helpers\Url;

if(Yii::$app->request->get('search')) {
    $url = Url::to(array_filter(['categories/search', 'category_alias' => Yii::$app->request->get('category_alias'), 'all' => Yii::$app->request->get('all'), 'search' => Yii::$app->request->get('search')]));
} else {
    $url = Url::to(array_filter(['categories/view', 'category_alias' => Yii::$app->request->get('category_alias'), 'all' => Yii::$app->request->get('all')]));
}
?>
<form action="<?= $url ?>" method="get">
    <?php if (Yii::$app->request->get()) {
        foreach (array_filter(Yii::$app->request->get()) as $id => $get) {
            if (!in_array($id, [$getParamName, 'category_alias', 'all'])) {
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
        <div class="filter-search">
            <input type="text" placeholder="Поиск" class="input_filter-search">
        </div>

        <ul class="filter-select-all">
            <li><a href="javascript:void(0)" class="filter-btn-on">Выделить все</a></li>
            <li><a href="javascript:void(0)" class="filter-btn-off">Убрать все</a></li>
        </ul>

        <div class="filter-block">
            <?php if (isset($getParam) && !empty($getParam)) { ?>
                <?php foreach ($getParam as $item) { ?>
                    <li>
                        <label for="<?= $idAttrName ?>-<?= $checkboxList[$item]->id ?>"
                               class="checkbox checkbox-filter">
                            <input type="checkbox" name="<?= $getParamName ?>[]"
                                   id="<?= $idAttrName ?>-<?= $checkboxList[$item]->id ?>"
                                   value="<?= $checkboxList[$item]->id ?>" checked>
                            <i></i><?= $checkboxList[$item]->$attrName ?>
                        </label>
                        <span class="badge">(<?= $checkboxList[$item]->count ?>)</span>
                    </li>
                <? } ?>
            <? } ?>

            <?php foreach ($checkboxList as $item) { ?>
                <?php if ($item->count > 0) { ?>
                    <?php if (isset($getParam) && !empty($getParam) && in_array($item->id, $getParam)) continue; ?>
                    <li>
                        <label for="<?= $idAttrName ?>-<?= $item->id ?>" class="checkbox checkbox-filter">
                            <input type="checkbox" name="<?= $getParamName ?>[]"
                                   id="<?= $idAttrName ?>-<?= $item->id ?>"
                                   value="<?= $item->id ?>">
                            <i></i><?= $item->$attrName ?>

                        </label>
                        <span class="badge">(<?= $item->count ?>)</span>
                    </li>
                <? } ?>
            <? } ?>
        </div>

        <div class="filter-button">
            <button class="btn">Применить</button>
        </div>
    </section>
</form>






