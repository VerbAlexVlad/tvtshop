<?php

use app\widgets\CategoriesList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

if (Yii::$app->request->get('search')) {
    $url = Url::to(array_filter(['categories/search', 'category_alias' => Yii::$app->request->get('category_alias'), 'all' => Yii::$app->request->get('all'), 'search' => Yii::$app->request->get('search')]));
} else {
    $url = Url::to(array_filter(['categories/view', 'category_alias' => Yii::$app->request->get('category_alias'), 'all' => Yii::$app->request->get('all')]));
}
if (Yii::$app->request->isAjax) {
    JqueryAsset::register($this);
    $this->registerCssFile('@web/css/categories-filter.css', ['position' => yii\web\View::POS_HEAD]);
    $this->registerJsFile('@web/js/categories-filter.js', ['position' => yii\web\View::POS_END]);
}

/* @var $getParamName */
/* @var $categoryInfo */
?>

<form action="<?= $url ?>" method="get">
    <h2 class="h2_sizes text-center"><?= Yii::t('app', 'Список категорий') ?></h2>
    <?php if (Yii::$app->request->get()) {
        foreach (array_filter(Yii::$app->request->get()) as $id => $get) {
            if (!in_array($id, [$getParamName, 'category_alias', 'all'], true)) {
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

    <section class="checkbox-area">
        <ul>
            <?php try {
                echo CategoriesList::widget([
                    "categoryInfo" => $categoryInfo,
                    'all' => Yii::$app->request->get('all') ?? 'all',
                    'tpl' => 'filter-categories',
                    'categories' => Yii::$app->request->get('categories')
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
            } ?>
        </ul>
        <div class="filter-button text-center">
            <button class="btn">Применить</button>
        </div>
    </section>
</form>