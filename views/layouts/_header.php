<?php

use app\models\Cart;
use app\models\FavoritesProducts;
use app\models\Userparams;
use app\widgets\Fancybox;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<header class="header">
    <div class="header-top">
        <nav class="flex container">
            <ul class="left_header-top flex">
                <?php if (Yii::$app->user->can('viewAdminPage')) { ?>
                    <li>
                        <?= Html::a('Админка', Url::to(['/admin'])) ?>
                    </li>
                    <li>
                        <?= Html::a('RBAC', Url::to(['/rbac'])) ?>
                    </li>
                <?php } else { ?>
                    <li>
                        <?= Fancybox::widget([
                            "fancyboxText" => "Доставка",
                            "fancyboxTitle" => "Показать информацию о доставке",
                            "fancyboxUrl" => Url::to(['site/delivery']),
                        ]) ?>
                    </li>
                    <li>
                        <?= Fancybox::widget([
                            "fancyboxText" => "Оплата",
                            "fancyboxTitle" => "Показать информацию об оплате",
                            "fancyboxUrl" => Url::to(['site/payment']),
                        ]) ?></li>
                    <li>
                        <?= Fancybox::widget([
                            "fancyboxText" => "Контакты",
                            "fancyboxTitle" => "Показать информацию о контактах",
                            "fancyboxUrl" => Url::to(['site/contact']),
                        ]) ?>
                    </li>
                <?php } ?>
            </ul>

            <ul class="right_header-top flex">
                <li><span itemprop="email">info@tvtshop.ru</span></li>
                <li><a href="tel:89953288200"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                        <span itemprop="telephone">8-(995)-328-82-00</span></a></li>
                <li>
                    <?= Fancybox::widget([
                        "fancyboxText" => "Заказать звонок",
                        "fancyboxTitle" => "Показать информацию о доставке",
                        "fancyboxUrl" => Url::to(['site/order-call',
                            'thisUrl' => Yii::$app->request->absoluteUrl
                        ]),
                    ]) ?>
                </li>
            </ul>
        </nav>
    </div>

    <div class="header-bottom">
        <div class="flex block_header-bottom container">
            <a href="<?= Url::to(['favorites-products/index']) ?>" class="favorites-link_header">
                Избранное (<span
                        class="header-count-product-in-favorites"><?= (new FavoritesProducts())->countProductInFavoritesProducts() ?></span>)
            </a>
            <a href="/" class="logo_header"><img src="<?= Url::to(['/img/logo.png']) ?>" alt=""></a>
            <?php if (Yii::$app->user->isGuest) { ?>
                <?= Fancybox::widget([
                    "fancyboxClass" => "user-reg-header",
                    "fancyboxText" => "Войти",
                    "fancyboxTitle" => "Войти или зарегистрироваться",
                    "fancyboxUrl" => Url::to(['site/login']),
                ]) ?>
            <?php } else {
                echo Html::a('Выйти', Url::to(['site/logout']), ['class' => 'user-reg-header']);
            } ?>
            <div class="cart_user-panel">
                <a href="<?= Url::to(['cart/index']) ?>" class="cart_user-link">
                    Корзина (<span
                            class="header-count-product-in-cart"><?= (new Cart())->getCountProductsInCart() ?></span>)
                </a>
            </div>
        </div>
    </div>
</header>

<header class="header-nav">
    <nav class="container">
        <ul class="flex">
            <li>
                <ul id="gn-menu" class="gn-menu-main">
                    <li class="gn-trigger">
                        <a href="#menu" class="gn-icon gn-icon-menu" style=""><span><i
                                        class="icon fa fa-align-justify fa-fw"></i> Каталог <i
                                        class="fas fa-caret-down"></i></span></a>

                        <nav id="menu" hidden="">
                            <div id="panel-menu">
                                <ul>
                                    <li class="category-my-size">
                                        <span><b>МУЖСКИЕ</b> товары всех размеров</span>
                                        <ul>
                                            <?= \app\widgets\CategoriesList::widget([
                                                "tpl" => "mmenu",
                                                'all' => "man"
                                            ]) ?>
                                            <li id="donate">
                                                <a href="#">
                                                    <p style="color: red">* Категории отображаются с учетом выбранных
                                                        фильтров!!!</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="category-my-size">
                                        <span><b>ЖЕНСКИЕ</b> товары всех размеров</span>
                                        <ul>
                                            <?= \app\widgets\CategoriesList::widget([
                                                "tpl" => "mmenu",
                                                'all' => "woman"
                                            ]) ?>
                                        </ul>
                                    </li>

                                    <li class="category-my-size">
                                        <span>Товары <b>МОИХ</b> размеров</span>
                                        <ul>
                                            <?php if ((new Userparams())->getUserParamId()) { ?>
                                                <?= \app\widgets\CategoriesList::widget([
                                                    "tpl" => "mmenu",
                                                    'all' => "me"
                                                ]) ?>
                                            <?php } else { ?>
                                                <li class="left-menu">
                                                    <a href="<?= Url::to(['userparams/update']) ?>">
                                                        Найти одежду <b>МОИХ</b> размеров
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>

                                    <li class="favorites-cat">
                                        <span><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Избранное</span>
                                        <ul>
                                            <li>
                                                <a style="display: inline;"
                                                   href="<?= Url::to(['favorites-products/index']) ?>">
                                                    <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                                                    Избранные товары
                                                </a>
                                            </li>
                                            <li>

                                                <a style="display: inline;"
                                                   href="<?= Url::to(['favorites-shops/index']) ?>">
                                                    <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                                                    Избранные склады
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>

                                <ul>
                                    <li class="shop-cat">
                                        <?= Fancybox::widget([
                                            "fancyboxText" => "Доставка",
                                            "fancyboxTitle" => "Показать информацию о доставке",
                                            "fancyboxUrl" => Url::to(['site/delivery']),
                                        ]) ?>
                                    </li>
                                    <li class="shop-cat">
                                        <?= Fancybox::widget([
                                            "fancyboxText" => "Оплата",
                                            "fancyboxTitle" => "Показать информацию об оплате",
                                            "fancyboxUrl" => Url::to(['site/payment']),
                                        ]) ?>
                                    </li>
                                </ul>

                                <ul>
                                    <li class="left-menu mm-listitem">
                                        <?= Html::a(
                                            "<i class='fas fa-child'></i> Параметры моего телосложения",
                                            Url::to(['userparams/update']),
                                            ["title" => 'Показать информацию о параметрах моего телосложения']
                                        ) ?>
                                    </li>
                                </ul>

                                <ul>
                                    <li class="left-menu mm-listitem">
                                        <a href="<?= Url::to(['cart/index']) ?>">
                                            Корзина (<span
                                                    class="header-count-product-in-cart"><?= (new Cart())->getCountProductsInCart() ?></span>)
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div id="panel-account">
                                <ul>
                                    <li>
                                        <a style="display: inline;" href="/user">
                                            <span class="glyphicon glyphicon-user"></span> Личный кабинет
                                        </a>
                                    </li>

                                    <li>
                                        <a style="display: inline;" href="/user/order">
                                            <span class="glyphicon glyphicon-gift"></span> Мои покупки
                                        </a>
                                    </li>

                                    <li>
                                        <a style="display: inline;" href="/site/logout">
                                            <span class="glyphicon glyphicon-off"></span> Выйти
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-fancybox="" style="display: inline;" data-type="ajax"
                                           data-options="{&quot;touch&quot; : false}"
                                           data-src="/userparam/handler/specified-parameter">
                                            <i class="fas fa-child"></i> Параметры моего телосложения
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </li>
                </ul>
            </li>
            <li class="floor-item">
                <?= Html::a(
                    '<strong>Мужские</strong> товары',
                    Url::to(['categories/view', 'all' => 'man']),
                    ["title" => 'Мужские товары']
                ) ?>
            </li>
            <li class="floor-item">
                <?= Html::a(
                    '<strong>Женские</strong> товары',
                    Url::to(['categories/view', 'all' => 'woman']),
                    ["title" => 'Женские товары']
                ) ?>
            </li>
            <li class="floor-item">
                <?php if (Userparams::getUserParamId()) { ?>
                    <?= Html::a(
                        'Товары <strong>моих</strong> размеров',
                        Url::to(['categories/view', 'all' => 'me']),
                        ["title" => 'Показать все товары моих размеров']
                    ) ?>
                <?php } else { ?>
                    <?= Fancybox::widget([
                        "fancyboxText" => "Товары <strong>моих</strong> размеров",
                        "fancyboxTitle" => "Поиск одежды по параметрам телосложения",
                        "fancyboxUrl" => Url::to(['userparams/update']),
                    ]) ?>
                <?php } ?>
            </li>
            <li class="floor-item">
                <?= Html::a(
                    'Бренды',
                    Url::to(['brands/index']),
                    ["title" => 'Женские товары']
                ) ?>
            </li>
            <li class="item-search">
                <form class="form_item-search" action="/categories/search" method="get">
                    <input class="input_item-search" type="search" name="search" placeholder="Поиск"
                           value="<?= Yii::$app->request->get('search') ?>">
                    <button class="button_item-search"></button>
                </form>
            </li>
        </ul>
    </nav>
</header>

