<?php

/* @var $this yii\web\View */
/* @var $model app\models\Userparams */

$this->title = Yii::t('app', 'Поиск одежды по параметрам телосложения');
//$this->title = Yii::t('app', 'Update Userparams: {name}', [
//    'name' => 1,
//]);

$this->params['breadcrumbs'][] = $this->title;

?>
<div>
    <div class="title container">
        <h1 class="h1_title"><?= $this->title ?></h1>

        <p>Привет дорогой друг! У нас есть крутая функция, которая поможет Тебе найти одежду и обувь, которая подходит
            тебе
            по всем параметрам!</p>
        <p>Для этого нужно указать Свои параметры телосложения в сантиметрах, выбрать пол, нажать на кнопку "Начать
            поиск" и
            через несколько секунд система найдет все, соответствующие указанным данным, товары!</p>
        <p>Не обязательно вводить все параметры. Можно указать любой, интересующий Вас, например, "длину стопы" и
            указать
            Ваш пол, и система будет искать совпадения только среди обуви, которая есть в наличии.</p>
    </div>

    <div class="userparams-update container">
        <?= \app\widgets\ParametersWidget::widget(['tpl' => 'parameters']) ?>
    </div>
</div>
