<?php

namespace app\controllers;

use app\models\ProductsSearch;
use Yii;
use app\models\FavoritesProducts;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FavoritesProductsController implements the CRUD actions for FavoritesProducts model.
 */
class FavoritesProductsController extends Controller
{
    public $layout = 'favorites-layout';

    /**
     * @return array[]
     */
    public function behaviors(): array
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
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'dataProvider' => (new ProductsSearch())->searchFavorites()
        ]);
    }

    /**
     * @param $product_id
     * @return bool|int|string|null
     */
    public function actionAddProductInFavoritesProducts($product_id)
    {
        if (!Yii::$app->user->isGuest) {
            $favoritesProducts = new FavoritesProducts();
            $favoritesProducts->setAttributes(['fp_user_id' => Yii::$app->user->id, 'fp_product_id' => $product_id]);
            $favoritesProducts->save();
        } else {
            $_SESSION['favorites_products'][$product_id] = $product_id;
        }
        return (new FavoritesProducts())->countProductInFavoritesProducts();
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new FavoritesProducts();

        if ($model->load(post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($product_id)
    {
        if (!Yii::$app->user->isGuest) {
            $favoriteProduct = FavoritesProducts::findOne(['fp_product_id' => $product_id]);
            if (!$favoriteProduct || !$favoriteProduct->delete()) {
                Yii::$app->session->setFlash('error', "Не удалось удалить");
            }
        } else if (isset($_SESSION['favorites_products'][$product_id]) && !empty($_SESSION['favorites_products'][$product_id])) {
            unset($_SESSION['favorites_products'][$product_id]);
        }

        return $this->renderAjax('index', [
            'dataProvider' => (new ProductsSearch())->searchFavorites(),
        ]);
    }

    /**
     * @param $id
     * @return FavoritesProducts
     * @throws NotFoundHttpException
     */
    protected function findModel($id): FavoritesProducts
    {
        if (($model = FavoritesProducts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
