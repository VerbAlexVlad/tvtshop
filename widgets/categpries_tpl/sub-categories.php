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
<?php if ($this->categoryInfo->name == 'root' || $category->id == $this->categoryInfo->id || ($start == true && ($depth == $category->depth - 1))) { ?>
    <?php if ($category->id == $this->categoryInfo->id) { ?>
        <?= $this->getMenuHtml($category->children, true, $this->categoryInfo->depth) ?>
    <?php } else { ?>
        <li>
            <noindex>
                <a rel="nofollow" href="<?= $url ?>">
                    <?= \yii\helpers\Html::img($category->getImage()->getUrl('150x'), ['style' => 'width:100%']) ?>
                    <span><?= $category->name ?><br>(<?= Yii::$app->inflection->pluralize($category->count, 'товар') ?>)</span>
                </a>
            </noindex>

            <?php if (isset($category->children) && !empty($category->children) && ($depth != $category->depth - 1)): ?>
                <ul class="category-products-list" style="">
                    <?= $this->getMenuHtml($category->children, true, $this->categoryInfo->depth) ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php } ?>
<?php } else { ?>
    <?= $this->getMenuHtml($category->children) ?>
<?php } ?>
