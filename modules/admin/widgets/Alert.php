<?php
namespace app\modules\admin\widgets;

use Yii;
use yii\helpers\Html;

class Alert
{
    public function begin(){
        $alertTypes = [
            'error'   => 'alert-danger',
            'danger'  => 'alert-danger',
            'success' => 'alert-success',
            'info'    => 'alert-info',
            'warning' => 'alert-warning'
        ];
      
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $flash) {
            if (!isset($alertTypes[$type])) {
                continue;
            }
            foreach ((array) $flash as $i => $message) {
                echo Html::tag(
                    'div',
                    $message,
                    [
                        'class' => 'alert ' . $alertTypes[$type],
                    ]
                );

            }

            $session->removeFlash($type);
        }
    }

}