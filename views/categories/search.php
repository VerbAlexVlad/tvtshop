<?php
use yii\helpers\Url;
use app\widgets\CategoriesList;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поиск';



$this->params['breadcrumbs'][] = $this->title;
?>

<section class="title container">
    <h1 class="h1_title"><?= $this->title ?></h1> <span
        class="number_title"><?= Yii::$app->inflection->pluralize($dataProvider ? $dataProvider->getTotalCount() : 0, 'товар') ?></span>
</section>

<section class="container flex">
    <aside class="category-aside">
        <ul class="left-category">
            <!--            --><?//= CategoriesList::widget([
            //                "categoryInfo" => $categoryInfo,
            //            ]) ?>
        </ul>
    </aside>

    <section class="products-category">
        <article>
            <main>
                <?php if($dataProvider) { ?>
                    <?= $this->render('_filter'); ?>

                    <?= $this->render('_product_list', [
                        'dataProvider' => $dataProvider,
                        'favoritesProducts' => $favoritesProducts
                    ]); ?>
                <?php } else { ?>
                    По данному запросу ни чего не найдено
                <?php } ?>
            </main>
        </article>
    </section>
</section>
