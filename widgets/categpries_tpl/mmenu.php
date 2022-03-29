<?php
$get = array_filter([
    'categories/view',
    'category_alias' => $category->alias,
    'all' => $this->all ?? false,
]);
$url = \yii\helpers\Url::to($get)
?>

<li class="left-menu">
    <?php if (isset($category['children']) && !empty($category['children'])) { ?>
        <span><?= $category->name ?> <span class="mm-counter"><?= $category->count ?></span></span>
        <ul>
            <li class="left-menu">
                <a href="<?= $url ?>"><em>Показать все товары</em></a>
            </li>

            <?= $this->getMenuHtml($category['children']) ?>
        </ul>
    <?php } else { ?>
        <a rel="nofollow" href="<?= $url ?>">
            <?= $category->name ?> <span class="mm-counter"><?= $category->count ?></span>
        </a>
    <?php } ?>
</li>
