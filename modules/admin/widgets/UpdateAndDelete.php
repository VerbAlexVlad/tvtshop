<?php

namespace app\modules\admin\widgets;

use Yii;
use yii\helpers\Html;

class UpdateAndDelete
{
    public function begin($model, $pjax = 1): string
    {
        $delete = Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => "Вы уверены, что хотите удалить этот элемент?",
                'method' => 'post',
                'pjax' => $pjax
            ],
            'title' => Yii::t('app', 'Удалить запись')
        ]);
        $update = Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);

        return "{$update}{$delete}";
    }
}