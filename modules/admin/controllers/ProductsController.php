<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Descriptions;
use app\modules\admin\models\Params;
use app\modules\admin\models\Products;
use app\modules\admin\models\ProductsSearch;
use app\modules\admin\models\Sizes;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new Products();
        $description_model = new Descriptions();

        if ($this->request->isPost && $model->load(post()) && $description_model->load(post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$model->save()) {
                    $transaction->rollBack();
                } else {
                    $model->link('productDescription', $description_model);

                    $transaction->commit();
                }

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'description_model' => $description_model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $description_model = $model->productDescription ?? new Descriptions();

        if ($this->request->isPost && $model->load(post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$flag = $model->save()) {
                    $transaction->rollBack();
                }

                if ($flag && $description_model->load(post()) && $description_model->validate()) {
                    $description_model->save(false);

                    $model->link('productDescription', $description_model);
                }

                $transaction->commit();

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'description_model' => $description_model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $param_id
     * @return string
     */
    function actionProductSizeParamList($param_id)
    {
        $size_info = Sizes::findOne($param_id);
        $model = Params::find()
            ->where(['id' => $param_id])
            ->with(['paramParameters'])
            ->asArray()
            ->limit(20)
            ->all();

        return $this->renderAjax('_product-sizes-param', [
            'size_info' => $size_info,
            'model' => $model,
        ]);
    }
}
