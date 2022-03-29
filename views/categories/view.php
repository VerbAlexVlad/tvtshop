<?php
use app\models\Cart;
use app\widgets\CategoriesList;
use app\widgets\ParametersWidget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $favoritesProducts array */
/* @var $categoryInfo */
/* @var $parentsCategories */

$this->title = $categoryInfo->getCategoryTitle(get());

if(isset($parentsCategories) && !empty($parentsCategories)) {
    foreach ($parentsCategories as $parentCategory) {
        $this->params['breadcrumbs'][] = [
            'label' => $parentCategory['name'],
            'url' => Url::to(['categories/view', 'category_alias' => $parentCategory['alias']])
        ];
    }
}


$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title container">
    <h1 class="h1_title"><?= $this->title ?></h1> <span
        class="number_title"><?= Yii::$app->inflection->pluralize($dataProvider->getTotalCount(), 'товар') ?></span>
</div>

<main class="container flex">
    <aside class="category-aside">
        <ul class="left-category">
            <?php try {
                echo CategoriesList::widget([
                    "categoryInfo" => $categoryInfo,
                    'all' => get('all') ?? 'all'
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
            } ?>
        </ul>
    </aside>

    <div class="products-category">
        <?= $this->render('_filter'); ?>

        <ul class="sub-categories">
            <?php try {
                echo CategoriesList::widget([
                    "categoryInfo" => $categoryInfo,
                    'all' => get('all') ?? 'all',
                    'tpl' => 'sub-categories'
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
            } ?>
        </ul>

        <?php if (get('all') !== null && get('all') === 'me') { ?>
            <?php try {
                echo ParametersWidget::widget([]);
            } catch (Exception $e) {
                echo $e->getMessage();
            } ?>
        <?php } ?>

        <?= $this->render('_product_list', ['dataProvider' => $dataProvider, 'favoritesProducts' => $favoritesProducts]); ?>

        <?php if (!empty($categoryInfo->categoriesDescriptions->description)) { ?>
            <div class="category-description">
                <?= $categoryInfo->categoriesDescriptions->description ?>
            </div>
        <?php } ?>

        <a href="<?= Url::to(['/cart/index']) ?>" class="cartFixed"
           style="<?= (new Cart())->getCountProductsInCart() <= 0 ? 'display:none' : false ?>">
            Перейти в корзину (<span
                class="header-count-product-in-cart"><?= (new Cart())->getCountProductsInCart() ?></span>)
        </a>
    </div>
</main>
