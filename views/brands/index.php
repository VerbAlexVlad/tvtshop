<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<style>
    .brand-abc a{
        line-height: 1;
        padding-bottom:10px
    }
    .brands-list{
        display:flex;
        flex-wrap:wrap;
    }
    .brand-item{
        display:inline-block;
        margin-right:15px;
    }
</style>
<div class="container">
    <h2>Бренды по алфавиту:</h2>
    <div class="brand-abc">
        <?php foreach($brands_abc as $id=>$brand_abc) { ?>
            <h3><?= $id ?></h3>
            <div class="brands-list">
                <?php foreach($brand_abc as $id=>$brand) {
                    echo \yii\helpers\Html::a(
                        $brand,
                        \yii\helpers\Url::to(['categories/view', 'all' => 'all', 'brands' => [$id]]),
                        ['class' => 'brand-item']
                    );
                } ?>
            </div>
        <?php } ?>
    </div>
</div>

