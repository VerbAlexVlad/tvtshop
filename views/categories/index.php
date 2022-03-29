<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('https://unpkg.com/swiper/swiper-bundle.min.js');
$this->registerCssFile('https://unpkg.com/swiper/swiper-bundle.min.css');
$js = <<< JS
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        pagination: {
          el: ".swiper-pagination",
          clickable: true
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev"
        }
      });
JS;
$this->registerJs($js, $position = yii\web\View::POS_END, $key = null);
?>
<style>
    .swiper-container {
        width: 100%;
        height: 100%;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;

        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .swiper-container {
        margin-left: auto;
        margin-right: auto;
    }
</style>
<div class="swiper-container mySwiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide"><img src="https://outmaxshop.ru/images/NEWS2021/MAY/Adidas_SL_7200/ADIDAS_Sl7200_1920x1080.jpg" alt=""></div>
        <div class="swiper-slide">Slide 2</div>
    </div>

    <div class="swiper-button-next"></div>

    <div class="swiper-button-prev"></div>

    <div class="swiper-pagination"></div>
</div>
<div class="container">

</div>
