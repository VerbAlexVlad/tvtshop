<?php

use yii\helpers\Url;

$get_array = Yii::$app->request->get();
unset($get_array['all']);

?>

<ul class="sort-button-list">
    <?php foreach ($floor_list as $item) { ?>
        <?php if ($item['all'] == 'me') { ?>
            <li class="sort-button-item">
                <?php if (\app\models\Userparams::getUserParamId()) { ?>
                    <a class="btn"
                       href="<?= Url::to(array_merge(['categories/view', 'all' => $item['all']])) ?>"
                    >
                        <?= $item['name'] ?> (<?= $item['count'] ?>)
                    </a>
                <?php } else { ?>
                    <a class="btn"
                       href="<?= Url::to(['userparams/update']) ?>"
                    >
                        <?= $item['name'] ?> <br><span style='color: #F93C00;'>(Нажмите для поиска)</span>
                    </a>
                <?php } ?>
            </li>
        <?php } else { ?>
            <?php
            if(Yii::$app->request->get('search')) {
                $url = Url::to(array_merge(['categories/search', 'all' => $item['all']], array_filter($get_array)));
            } else {
                $url = Url::to(array_merge(['categories/view', 'all' => $item['all']], array_filter($get_array)));
            }
            ?>
            <li class="sort-button-item">
                <a class="btn"
                   href="<?= $url ?>"
                >
                    <?= $item['name'] ?> (<?= $item['count'] ?>)
                </a>
            </li>
        <?php } ?>

    <?php } ?>
</ul>




