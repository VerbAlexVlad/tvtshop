<?php
namespace app\controllers;

use app\models\Brands;
use app\models\Products;
use yii\helpers\ArrayHelper;

class BrandsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $brands_id = Brands::find()
            ->joinWith([
                'models' => function ($productModel) {
                    $productModel->joinWith([
                        'products'=>function($products){
                            $products->active();
                        }
                    ]);
                },
            ])
            ->distinct()
            ->column();

        $brands_list = Brands::find()
            ->where(['in', 'id', $brands_id])
            ->orderBy(['brand_name' => SORT_ASC])
            ->asArray()
            ->all();

        foreach($brands_list as $id=>$brand_list) {
            $brands_list[$id]['first_ch'] = mb_substr($brand_list['brand_name'], 0, 1);
        }

        $brands_abc = ArrayHelper::map($brands_list, 'id', 'brand_name', 'first_ch');

        return $this->render('index',
            [
                'brands_abc' => $brands_abc
            ]);
    }

}
