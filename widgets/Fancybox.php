<?php

namespace app\widgets;
use yii\helpers\Html;
use yii\base\Widget;

class Fancybox extends Widget
{
    /**
     * @var
     */
    public $fancyboxClass = "";
    public $fancyboxTitle;
    public $fancyboxUrl;
    public $fancyboxText = "";

    /**
     * @return string|void
     */
    public function run()
    {
        return Html::a($this->fancyboxText, '#',
            [
                "class" => $this->fancyboxClass,
                "title" => $this->fancyboxTitle,
                "data-src" => $this->fancyboxUrl,
                "data-fancybox" => true,
                "data-type" => "ajax",
                "data-options" => '{"touch" : false, "autoFocus" : false}',
            ]
        );
    }
}
