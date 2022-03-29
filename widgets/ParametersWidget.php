<?php

namespace app\widgets;
use app\models\Parameters;
use app\models\Userparams;
use yii\base\Widget;

class ParametersWidget extends Widget
{
    public $tpl;
    public $menuHtml;

    public function init()
    {
        parent::init();
        if ($this->tpl === null) {
            $this->tpl = 'parameters_in_category';
        }
        $this->tpl .= '.php';
    }

    public function run()
    {
        $param_list = (new \app\models\Parameters)->getAllMainParameters();

        $userParam_list = [];
        if ($userParam_id = (new \app\models\Userparams)->getUserParamId()){
            $userParam_list = Userparams::find()
                ->where(['userparam_param_num' => $userParam_id])
                ->indexBy('userparam_parameters_id')
                ->all();
        }

        $this->menuHtml = $this->paramToTemplate($param_list, $userParam_list);

        return $this->menuHtml;
    }

    protected function paramToTemplate($param_list, $userParam_list)
    {
        ob_start();
        include __DIR__ . '/parameters_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}
