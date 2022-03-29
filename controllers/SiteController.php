<?php

namespace app\controllers;

use app\models\AccountActivation;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\OrderCall;
use app\models\ResetpasswordForm;
use app\models\SendEmailForm;
use app\models\SignupForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionPayment()
    {
        $this->layout = false;

        return $this->render('payment');
    }

    /**
     * @return string
     */
    public function actionDelivery()
    {
        $this->layout = false;

        return $this->render('delivery');
    }

    /**
     * @return string
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                Yii::$app->session->setFlash('success', "Вы успешно авторизованны");
                return $this->goBack();
            }
        }

        $model->password = '';
        if (Yii::$app->user->isGuest) {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('login', [
                    'model' => $model,
                ]);
            }

            return $this->render('login', [
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('success', "Вы уже авторизованы");
            return $this->goHome();
        }
    }

    /**
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    Yii::$app->session->setFlash('success', "Вы успешно авторизованны");
                    return $this->goBack();
                }
            }
        }

        if (Yii::$app->user->isGuest) {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('signup', [
                    'model' => $model,
                ]);
            }

            return $this->render('signup', [
                'model' => $model,
            ]);
        } else {
            Yii::$app->session->setFlash('success', "Вы уже авторизованы");
            return $this->goHome();
        }
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $this->layout = false;

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * @param null $thisUrl
     * @return string
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionOrderCall($thisUrl = null)
    {

        $model = new OrderCall();

        $model->this_url = \yii\helpers\HtmlPurifier::process($thisUrl);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->save()) {
                    $model->sendOrderCall('order-call');

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Ваш запрос был удачно отправлен. Ожидайте звонка менеджера.');
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось оформить запрос на звонок. Попробуйте перезагрузить страницу.'));
                }
            } catch (\Exception|\Throwable $e) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось оформить запрос на звонок. Попробуйте перезагрузить страницу.'));

                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->renderAjax('order-call', [
            'model' => $model,
            'thisUrl' => $thisUrl,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionSendemail()
    {
        $model = new SendEmailForm();

        if ($model->load(post())) {
            if ($model->validate()) {
                if ($model->sendEmail()):
                    Yii::$app->getSession()->setFlash('success', 'На указанный электронный адрес выслано сообщение с ссылкой на восстановление пароля. Проверьте ваш почтовый ящик.');
                    return $this->goHome();
                else:
                    Yii::$app->getSession()->setFlash('error', 'Нельзя сбросить пароль.');
                endif;
            }
        }

        return $this->render(
            'sendEmail',
            [
                'model' => $model
            ]
        );
    }

    /**
     * @param $key
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionResetpassword($key)
    {
        try {
            $model = new ResetpasswordForm($key);
        } catch (InvalidParamException $e) {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
            return $this->redirect(['/site/login']);
        }
        if ($model->load(post())) {
            if ($model->validate() && $model->resetpassword()) {
                Yii::$app->getSession()->setFlash('success', 'Пароль успешно изменен.');
                return $this->redirect(['/site/login']);
            }
        }
        return $this->render(
            'resetpassword',
            [
                'model' => $model
            ]
        );
    }


    /**
     * @param $key
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionActivateaccount($key): Response
    {
        try {
            $user = new AccountActivation($key);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($user->activateAccount()):
            Yii::$app->session->setFlash('success', 'Активация прошла успешно. <strong>' . Html::encode($user->username) . '</strong> вы теперь в точьВточь!!!');

            return $this->redirect(Url::to(['/login']));
        else:
            Yii::$app->session->setFlash('error', 'Ошибка активации.');
            Yii::error('Ошибка при активации.');
            return $this->redirect(Url::to(['/login']));
        endif;
    }
}
