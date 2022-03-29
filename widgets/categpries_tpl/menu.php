<?php
$get = array_filter([
    'categories/view',
    'category_alias' => $category->alias,
    'colors' => $_GET['colors'] ?? false,
    'sizes' => $_GET['sizes'] ?? false,
    'brands' => $_GET['brands'] ?? false,
    'product_price_from' => $_GET['product_price_from'] ?? false,
    'product_price_to' => $_GET['product_price_to'] ?? false,
    'seasons' => $_GET['seasons'] ?? false,
    'all' => $_GET['all'] ?? false,
]);
$url = \yii\helpers\Url::to($get);
?>

<li <?= $category->depth == 1 ? "class='main-depth'" : false ?>>
    <a rel="nofollow" href="<?= $url ?>">
        <span><?= $category->name ?></span>

        <?php if (isset($category['children']) && !empty($category['children'])) { ?>
            <span class='badge'><?= $category->count ?> <i class="fa fa-angle-down"></i></span>
        <?php } else { ?>
            <span class='badge pull-right'><?= $category->count ?></span>
        <?php } ?>
    </a>
  
    <?php if (isset($category['children']) && !empty($category['children'])): ?>
        <ul class="category-products-list" style="">
            <li>
                <a rel="nofollow" href="<?= $url ?>" class="all-products">
                    <em><span>Показать все товары</span></em>
                </a>
            </li>

            <?= $this->getMenuHtml($category['children']) ?>
        </ul>
    <?php endif; ?>
</li>
