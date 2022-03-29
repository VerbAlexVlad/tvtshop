<?php

namespace app\controllers;

use app\models\Categories;
use app\models\OrderItems;
use app\models\Params;
use app\models\Products;
use app\models\ProductSizes;
use app\models\ProductsSearch;
use app\models\Userparams;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    public $layout = 'product-layout';
    public array $mySizes = [];


    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(): string
    {
        $searchModel = new ProductsSearch();

        $model = $searchModel->searchProduct(Yii::$app->request->queryParams);
        $categoryInfo = (new Categories)->getCategoryInfo($model->productModel->category->alias);

        // Ищем все категории
        $allCategories = (new Categories)->getAllCategories();

        // Ищем всех предков и подкатегории
        $categoryParentsAndChildren = (new Categories)->getParentsAndChildren($allCategories, $categoryInfo);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view_in_modal', [
                'model' => $model,
                'categoryParentsAndChildren' => $categoryParentsAndChildren,
            ]);
        }
        return $this->render('view', [
            'model' => $model,
            'categoryParentsAndChildren' => $categoryParentsAndChildren,
        ]);
    }

    /**
     * @param $product_alias
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    function actionProductSizeList($product_alias, $oneClickForm = false)
    {
        $params['product_alias'] = $product_alias;
        $model = (new ProductsSearch())->searchProductSizes($params);

        return $this->renderAjax('_product-sizes', [
            'model' => $model
        ]);
    }


    /**
     * @param string $product_alias
     * @return string
     * @throws NotFoundHttpException
     */

    function actionProductSizeListOneClick($product_alias)
    {
        $params['product_alias'] = $product_alias;
        $productsModel = (new ProductsSearch())->searchProductSizes($params);
        $orderModel = (new \app\models\Orders(['scenario' => \app\models\Orders::SCENARIO_CREATE_ORDER_IN_ONE_CLICK]));
        $productSizesModel = (new \app\models\ProductSizes);

        if ($orderModel->load(post()) && $orderModel->validate() && $productSizesModel->load(post()) && $productSizesModel->validate() && post('button') == 1) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (isset($productsModel->productSizes[$productSizesModel->id]) && $orderModel->save()) {
                    $orderItems = new OrderItems();
                    $orderItems->setAttributes([
                        'oi_product_size_id' => $productSizesModel->id,
                        'oi_shop_id' => $productsModel->product_shop_id,
                        'oi_price' => $productsModel->currentPrice,
                        'product_discount_id' => $productsModel->product_discount,
                        'oi_qty_item' => 1
                    ]);

                    if($orderItems->save()) {
                        $orderModel->link('orderItems', $orderItems);

                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Ваш запрос был удачно отправлен. Ожидайте звонка менеджера.');
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Не удалось оформить заказ.');
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Не удалось оформить заказ.');
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->renderAjax('_product-sizes-one-click', [
            'productSizesModel' => $productSizesModel,
            'productsModel' => $productsModel,
            'orderModel' => $orderModel
        ]);
    }

    /**
     * @param $param_id
     * @return string
     */
    function actionProductSizeParamList($param_id)
    {
        $model = Params::find()
            ->where(['id' => $param_id])
            ->with(['paramParameters'])
            ->asArray()
            ->limit(20)
            ->all();

        if ($userParamId = (new Userparams())->getUserParamId()) {
            $userParam = Userparams::find()
                ->where(['userparam_param_num' => $userParamId])
                ->indexBy('userparam_parameters_id')
                ->asArray()
                ->limit(20)
                ->all();
        }

        return $this->renderAjax('_product-sizes-param', [
            'model' => $model,
            'userParam' => $userParam ?? false
        ]);
    }

    /**
     * @param $id
     * @return Products|null
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }
    }
}
