<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Brands;
use app\modules\admin\models\BrandsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * BrandsController implements the CRUD actions for Brands model.
 */
class BrandsController extends Controller
{
    /**
     * @return array|string[]|\string[][][][]
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
        $searchModel = new BrandsSearch();
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
     * @param $id
     * @return string
     * @throws NotFoundHttpException
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
     * @param $id
     * @return Brands|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Brands::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Brands();

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
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
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
                $searchModel = new BrandsSearch();
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
     * @return \string[][]|\string[][][]
     * @throws \yii\db\Exception
     */
    public function actionSearchBrands($q = null, string $message = 'Список пуст. Нажмите на "+" для добавления.')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        $query = \app\models\Brands::find()
            ->select(['id', 'brand_name as text']);

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
