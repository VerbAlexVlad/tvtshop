<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Categories;
use app\modules\admin\models\CategoriesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller
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
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categories model.
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
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categories();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $model->image = UploadedFile::getInstance($model, 'image');

                if ($model->image) {
                    $model->upload();
                }
              
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $node1 = Categories::findOne([$model->parent_id]);
            $model->prependTo($node1)->save(); // inserting new node
          
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->image) {
                $model->deleteImages();
                $model->upload();
            } 
          
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
  
  
    public function actionProductList($categories, $root = false)
    {
        if($root){
            $cat_list[] = ['text' => 'Самостоятельная категория', 'id' => 1];
        }
        foreach ($categories as $item) {
            if ($item->children) {
                $cat_list[] = ['text' => $item->name, 'id' => $item->id];
                $cat_list[] = ['children' => $this->actionProductList($item->children)];
            } else {
                $cat_list[] = ['text' => $item->name, 'id' => $item->id];
            }
        }

        return $cat_list;
    }
        /**
     * @return mixed
     */
    public function actionCategoryList($q = false, $message = 'Не найдено')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if(!$q){
            $categories = (new \app\models\Categories)->getAllCategoriesInTreeView();

            $out['results'] = $this->actionProductList($categories->children, true);
        } else {
            $out = ['results' => ['id' => '', 'text' => '']];

            $query = \app\models\Categories::find()
                ->select(['id', 'name as text']);

            if (!is_null($q)) {
                $query->having(['like', 'text', $q]);
                $command = $query->createCommand();
                $data = $command->queryAll();
                $out['results'] = array_values($data);
            } 

            if (!$out['results']) {
                $out = ['results' => [['text' => $message]]];
            }
        }

        return $out;
    }
}
