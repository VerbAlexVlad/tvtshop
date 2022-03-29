<?php

namespace app\controllers;

use app\models\Cart;
use app\models\ProductSizes;
use app\models\ProductSizesSearch;
use app\models\Products;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * CartController implements the CRUD actions for Cart model.
 */
class CartController extends Controller
{
    public $layout = 'cart-layout';


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Cart models.
     * @return string
     */
    public function actionIndex(): string
    {
        $sizes_in_cart = (new Cart())->getProductsInCart();

        if (!empty($sizes_in_cart)) {
            $searchModel = new ProductSizesSearch();
            $dataProvider = $searchModel->searchSizesInCart($sizes_in_cart);
        }

        return $this->render('index', [
            'sizes_in_cart' => $sizes_in_cart ?? false,
            'dataProvider' => $dataProvider ?? false,
        ]);
    }

    function actionAddSizeInCart()
    {
        if ($size = $_GET['size'] ?? false) {
            $size_id = (integer)$size;

            if (!empty($size_id)) {
                $productInfo = Products::find()
                    ->where(['product_alias' => $_GET['product_alias']])
                    ->limit(1)
                    ->one();

                $product_size_id = ProductSizes::find()
                    ->where(['ps_product_id' => $productInfo->id])
                    ->andWhere(['ps_size_id' => $size_id])
                    ->limit(1)
                    ->one();

                if (!Yii::$app->user->isGuest) {
                    $cart = (new Cart())->getProductInCart($product_size_id->id);

                    if (!isset($cart)) {
                        $cart = new Cart;

                        $cart->setAttributes([
                            'user_id' => Yii::$app->user->id,
                            'product_id' => $productInfo->id,
                            'product_size_id' => $product_size_id->id,
                            'qty' => 1
                        ]);

                        if (!$cart->save()) {
                            return Json::encode(['error' => 'Не удалось добавить товар в корзину, попробуйте позже']);
                        }
                    }
                } else if (!isset($_SESSION['sizes_in_cart'][$product_size_id->id])) {
                    $_SESSION['sizes_in_cart'][$product_size_id->id] = [
                        'product_id' => $productInfo->id,
                        'qty' => 1,
                    ];
                }

                $result = ['count' => (new Cart())->getCountProductsInCart()];
            } else {
                $result = ['error' => 'Не удалось добавить товар в корзину, попробуйте позже'];
            }
        }
        return Json::encode($result);
    }

    /**
     * @param $product_size_id
     * @param $count
     * @return false
     */
    public function actionCountProducts($product_size_id, $count)
    {
        $sizes_in_cart = (new Cart())->getProductsInCart();

        if (!Yii::$app->user->isGuest) {
            if (isset($sizes_in_cart[$product_size_id])) {
                $sizes_in_cart[$product_size_id]->qty = $count;
                if (!$sizes_in_cart[$product_size_id]->save()) {
                    return false;
                }
            }
        } else if (isset($_SESSION['sizes_in_cart'][$product_size_id]) && !empty($_SESSION['sizes_in_cart'][$product_size_id])) {
            $_SESSION['sizes_in_cart'][$product_size_id]['qty'] = $count;
            $sizes_in_cart = $_SESSION['sizes_in_cart'];
        }


        if (!empty($sizes_in_cart)) {
            $searchModel = new ProductSizesSearch();
            $dataProvider = $searchModel->searchSizesInCart($sizes_in_cart);
        }

        return $this->renderAjax('index', [
            'sizes_in_cart' => $sizes_in_cart ?? false,
            'dataProvider' => $dataProvider ?? false,
        ]);
    }

    /**
     * @param $product_size_id
     * @param $count
     * @return false|float[]|int[]
     */
    public function actionDelete($product_size_id)
    {
        $sizes_in_cart = (new Cart())->getProductsInCart();

        if (!Yii::$app->user->isGuest) {
            if (!isset($sizes_in_cart[$product_size_id]) || !$sizes_in_cart[$product_size_id]->delete()) {
                return false;
            }

            unset($sizes_in_cart[$product_size_id]);
        } else {
            if (isset($_SESSION['sizes_in_cart'][$product_size_id]) && !empty($_SESSION['sizes_in_cart'][$product_size_id])) {
                unset($_SESSION['sizes_in_cart'][$product_size_id]);
            }
            $sizes_in_cart = $_SESSION['sizes_in_cart'];
        }

        if (!empty($sizes_in_cart)) {
            $searchModel = new ProductSizesSearch();
            $dataProvider = $searchModel->searchSizesInCart($sizes_in_cart);
        }

        return $this->renderAjax('index', [
            'sizes_in_cart' => $sizes_in_cart ?? false,
            'dataProvider' => $dataProvider ?? false,
        ]);
    }
}