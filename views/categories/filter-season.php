<?php
use yii\helpers\Url;
$get_array = Yii::$app->request->get();
unset($get_array['season']);
?>

<ul class="sort-button-list">
    <?php foreach ($season_list as $item) { ?>
        <?php
        if(Yii::$app->request->get('search')) {
            $url = Url::to(array_merge(['categories/search', 'season' => $item->id], $get_array));
        } else {
            $url = Url::to(array_merge(['categories/view', 'season' => $item->id], $get_array));
        }
        ?>
        <li class="sort-button-item">
            <a class="btn"
               href="<?= $url ?>"
            >
                <?= $item['season_name'] ?> (<?= $item['count'] ?>)
            </a>
        </li>
    <?php } ?>
</ul>




