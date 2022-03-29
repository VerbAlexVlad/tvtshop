<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Models;
use app\modules\admin\models\ModelsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

class ModelsController extends Controller
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
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ModelsSearch();
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
     * Displays a single Models model.
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
     * Creates a new Models model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Models();

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
     * Updates an existing Models model.
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
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
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
                $searchModel = new ModelsSearch();
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
     * Finds the Models model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Models the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Models::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @param string|null $q
     * @param string $message
     * @return \array[][]|\string[][]|\string[][][]
     * @throws \yii\db\Exception
     */
    public function actionSearchModels(string $q = null, string $message = 'Список пуст. Нажмите на "+" для добавления.')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        $query = \app\modules\admin\models\Models::find()
            ->select(['models.id', 'CONCAT(`categories`.`name`, ". ", `brands`.`brand_name`, ". ", `models`.`model_name`) as text'])
            ->joinWith(['brand', 'categories'])
            ->distinct('id');

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
