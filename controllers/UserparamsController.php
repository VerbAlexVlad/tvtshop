<?php

namespace app\controllers;

use app\models\Floor;
use app\models\Parameters;
use app\models\Params;
use app\models\ParamUserparam;
use Yii;
use app\models\Userparams;
use app\models\UserparamsSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserparamsController implements the CRUD actions for Userparams model.
 */
class UserparamsController extends Controller
{
//     public $layout = 'category-layout';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
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
     * Lists all Userparams models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserparamsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Userparams model.
     * @param integer $id
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
     * Creates a new Userparams model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Userparams();

        if ($model->load(post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Userparams model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {

        $param_list = (new Parameters())->getAllMainParameters();
        $userParam_list = [];

        if ($userParam_id = (new Userparams())->getUserParamId()){
            $userParam_list = Userparams::find()
                ->where(['userparam_param_num' => $userParam_id])
                ->indexBy('userparam_parameters_id')
                ->all();
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'param_list' => $param_list,
                'userParam_list' => $userParam_list,
            ]);
        }

        if (Yii::$app->request->post('Parameters')) {
            if (empty(array_filter(Yii::$app->request->post('Parameters')['id']))) {
                Yii::$app->session->setFlash('error', '<span></span>');
                Yii::$app->session->setFlash(
                    'error',
                    Html::tag('h3', 'Вы кое-что забыли').
                    Html::tag('p', 'Укажите <strong>ОДИН</strong> или <strong>НЕСКОЛЬКО</strong> параметров и нажмите на "Начать поиск"')
                );

            } else {
                $userparam_param_num = false;
                $allUserParameters = Userparams::find();
                foreach (array_filter(Yii::$app->request->post('Parameters')['id']) as $id => $postParameter) {
                    $allUserParameters->orWhere(['and', ['userparam_value' => $postParameter, 'userparam_parameters_id' => $id]]);
                }
                $allUserParameters = $allUserParameters->all();
                if ($allUserParameters) {
                    foreach (ArrayHelper::index($allUserParameters, null, 'userparam_param_num') as $id => $item) {
                        $groupUserparams = Userparams::find()
                            ->where(['userparam_param_num' => $id])
                            ->indexBy('userparam_parameters_id')
                            ->all();

                        if (count($groupUserparams) == count(array_filter(Yii::$app->request->post('Parameters')['id']))) {
                            $index = true;
                            foreach ($groupUserparams as $idGroupUserparam => $groupUserparam) {
                                if (Yii::$app->request->post('Parameters')['id'][$idGroupUserparam] != $groupUserparam->userparam_value) {
                                    $index = false;
                                    break;
                                }
                            }
                            if ($index) {
                                $userparam_param_num = $id;
                                break;
                            }
                        }
                    }

                }

                if (!$userparam_param_num) {
                    $userparam_param_num = Userparams::find()->max('userparam_param_num') + 1;

                    foreach (array_filter(Yii::$app->request->post('Parameters')['id']) as $id => $postParameter) {
                        $groupUserparams[$id] = new Userparams();
                        $groupUserparams[$id]->userparam_parameters_id = $id;
                        $groupUserparams[$id]->userparam_value = $postParameter;
                        $groupUserparams[$id]->userparam_param_num = $userparam_param_num;

                        if (!$groupUserparams[$id]->validate() || !$groupUserparams[$id]->save()) {
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }

                    $ps = ArrayHelper::index(Params::find()->select(['id', 'param_parameters_id', 'param_value', 'param_low_limit', 'param_up_limit'])->asArray()->all(), 'param_parameters_id', 'id');
                    $userparams = Userparams::find()
                        ->where(['userparam_param_num' => $userparam_param_num])
                        ->indexBy('userparam_parameters_id')
                        ->asArray()
                        ->all();

                    foreach ($ps as $p_id => $p) {
                        $index = true;
                        $issetIndex = false;
                        foreach ($p as $id => $item) {
                            if (in_array($id, array_keys($userparams))) {
                                $issetIndex = true;
                                $minPar = $item['param_value'] - $item['param_low_limit'];
                                $maxPar = $item['param_value'] + $item['param_up_limit'];
                                if ($userparams[$item['param_parameters_id']]['userparam_value'] < $minPar || $userparams[$item['param_parameters_id']]['userparam_value'] > $maxPar) {
                                    $index = false;
                                }
                            }
                        }

                        if ($index && $issetIndex) {
                            $paramUs = new ParamUserparam();
                            $paramUs->param_id = $p_id;
                            $paramUs->userparam_id = $userparam_param_num;
                            if (!$paramUs->save()) {
                                dd($paramUs->errors);
                            }
                        }
                    }
                }

                Userparams::setUserParamId($userparam_param_num);
                Floor::setUserFloor(Yii::$app->request->post('floor'));
                Yii::$app->session->setFlash(
                    'success',
                    Html::tag('h3', 'Успешно').
                    Html::tag('p', "На этой странице вы найдете все товары, подходящие <strong>Вам</strong> по размерам.").
                    Html::tag('p', "Так же в меню, находящемся в левом верхнем углу сайта ( <span class=\"gn-icon-alert gn-icon-menu-alert\"></span> ), во вкладке \"Товары <strong>МОИХ</strong> размеров\", Вы найдете список категорий, в которых есть данные товары.")
                );

                return $this->redirect(['categories/view', 'all' => 'me']);
            }
        }

        return $this->render('update', [
            'param_list' => $param_list,
            'userParam_list' => $userParam_list,
        ]);
    }

    /**
     * Deletes an existing Userparams model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Userparams model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Userparams the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Userparams::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
