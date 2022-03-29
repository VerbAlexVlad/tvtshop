<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Discounts;
use app\modules\admin\models\DiscountsSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DiscountsController implements the CRUD actions for Discounts model.
 */
class DiscountsController extends Controller
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Discounts models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DiscountsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Discounts model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Discounts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Discounts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Discounts::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Discounts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Discounts();

        if ($this->request->isPost && $model->load(post()) && post('button')) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save(false)) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Запись успешно добавлена');

                    if (Yii::$app->request->isAjax) {
                        return $this->renderAjax('view', [
                            'model' => $model,
                        ]);
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                }

                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Не удалось добавить запись');
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Не удалось добавить запись');
                $transaction->rollBack();
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Discounts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load(post()) && post('button')) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save(false)) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Запись успешно изменена');

                    if (Yii::$app->request->isAjax) {
                        return $this->renderAjax('view', [
                            'model' => $model,
                        ]);
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                }

                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Не удалось изменить запись');
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Не удалось изменить запись');
                $transaction->rollBack();
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Discounts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {
            if($model = $this->findModel($id)){
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Запись успешно удалена'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось удалить запись'));
            }

            if (Yii::$app->request->isAjax) {
                $searchModel = new DiscountsSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->renderAjax('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }

            return $this->redirect(['index']);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось удалить запись. Возможно она где-то используется.'));

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]);
            }

            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * @param false $q
     * @param string $message
     * @return \array[][]|\string[][]|\string[][][]
     * @throws \yii\db\Exception
     */
    public function actionSearchDiscounts($q = false, string $message = 'Список пуст. Нажмите на "+" для добавления.')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        $query = \app\models\Discounts::find()
            ->select(['id', 'discount_name as text']);

        if (!is_null($q)) {
            $query->having(['like', 'text', $q]);
        }

        $query->orderBy('text');

        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);

        if (!$out['results']) {
            $out = ['results' => [['text' => $message]]];
            return $out;
        }

        return $out;
    }
}
