<?php
use yii\helpers\Url;
$get_array = Yii::$app->request->get();
unset($get_array['sort']);

?>
<h1 class="sort-h1">Сортировка</h1>

<h2 class="sort-h2">По новизне:</h2>
<ul class="sort-button-list">
    <li class="sort-button-item"><a class="btn" href="<?= Url::to(array_merge(['categories/view', 'sort'=>'-id'], array_filter($get_array)))?>">Сначала новые</a></li>
    <li class="sort-button-item"><a class="btn" href="<?= Url::to(array_merge(['categories/view', 'sort'=>'id'], array_filter($get_array)))?>">Сначала старые</a></li>
</ul>

<h2 class="sort-h2">По цене:</h2>
<ul class="sort-button-list">
    <li class="sort-button-item"><a class="btn" href="<?= Url::to(array_merge(['categories/view', 'sort'=>'product_price'], array_filter($get_array)))?>">Сначала дешевле</a></li>
    <li class="sort-button-item"><a class="btn" href="<?= Url::to(array_merge(['categories/view', 'sort'=>'-product_price'], array_filter($get_array)))?>">Сначала дороже</a></li>
</ul>

<h2 class="sort-h2">По популярности:</h2>
<ul class="sort-button-list">
    <li class="sort-button-item"><a class="btn" href="<?= Url::to(array_merge(['categories/view', 'sort'=>'views'], array_filter($get_array)))?>">Сначала популярные</a></li>
    <li class="sort-button-item"><a class="btn" href="<?= Url::to(array_merge(['categories/view', 'sort'=>'-views'], array_filter($get_array)))?>">Сначала менее популярные</a></li>
</ul>



