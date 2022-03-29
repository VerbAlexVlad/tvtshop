<?php

namespace app\controllers;

use app\models\Cart;
use app\models\Floor;
use app\models\OrderItems;
use app\models\Orders;
use app\models\ProductSizesSearch;
use app\models\ProductsSearch;
use app\models\SearchOrders;
use app\models\User;
use app\models\Userparams;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    public $layout = 'order-layout';
    public array $mySizes = [];

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
     * Lists all Orders models.
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new SearchOrders();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param $id
     * @return Orders|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

//       $CategoryList = new \LapayGroup\RussianPost\CategoryList();
//   $categoryList = $CategoryList->parseToArray();

//       dd($categoryList);

        if (!$sizes_in_cart = (new Cart())->getProductsInCart()) {
            Yii::$app->session->setFlash('error', "Ваша корзина пуста, добавьте товары, для оформления заказа");
            return $this->goHome();
        }
        $calcInfo = null;
        $model = new Orders(['scenario' => Orders::SCENARIO_CREATE_ORDER]);

        $searchModel = new ProductSizesSearch();
        $dataProvider = $searchModel->searchSizesInCart($sizes_in_cart);

        if ($model->load(post()) && post('button')) {
            $message = [];
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if (!$user = (new User)::findByEmail($model->order_email)) {
                    $user = new User();
                    $password = generate_password(10);

                    $user->setAttributes([
                        'username' => $model->order_username,
                        'patronymic' => $model->order_patronymic,
                        'surname' => $model->order_surname,
                        'email' => $model->order_email,
                        'phone' => $model->order_phone,
                        'address_locality' => $model->order_phone,
                        'address_street' => $model->order_phone,
                        'address_house_number' => $model->order_phone,
                        'order_address_apartment_number' => $model->order_phone,
                        'param' => (new Userparams())->getUserParamId(),
                        'floor' => (new Floor())->getUserFloor(),
                        'status' => $user::STATUS_NOT_ACTIVE,

                    ]);

                    $user->setPassword($password);
                    $user->generateAuthKey();
                    $user->generateSecretKey();

                    if (!$user->save()) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Не удалось сохранить учетную запись");
                        return $this->render('create', [
                            'model' => $model,
                            'dataProvider' => $dataProvider,
                            'sizes_in_cart' => $sizes_in_cart,
                        ]);
                    }

                    $user->sendActivationEmailAndPassword('activation-email', $password);
                    $message[1] = "Для доступа в личный кабинет, активируйте ваш аккаун! На email - {$user->email} отправлено письмо с активацией аккаунта (На всякий случай проверьте папку спам";
                } else {
                    /** Если так и не был активирован, придет письмо с ссылкой на активацию на почтовый ящик */
                    if ($user->status !== User::STATUS_ACTIVE) {
                        try {
                            if ($user->sendActivationEmailAndPassword('activation-email', $user->password_hash)) {
                                $message[] = "Для доступа в личный кабинет, активируйте ваш аккаун! На email - {$user->email} отправлено письмо с активацией аккаунта (На всякий случай проверьте папку спам";
                            } else {
                                Yii::$app->session->setFlash('error', 'Ошибка оформления заказа, попробуйте позже.');
                                $transaction->rollBack();
                                return $this->refresh();
                            }
                        } catch (\Exception $e) {
                            $er_msg = $e->getMessage();
                            Yii::$app->session->setFlash('error', 'Ошибка оформления заказа, попробуйте позже.' . $er_msg);
                            Yii::error($er_msg);
                            return $this->render('create', [
                                'model' => $model,
                                'dataProvider' => $dataProvider,
                                'sizes_in_cart' => $sizes_in_cart,
                            ]);
                        }
                    }
                }

                $model->order_user_id = $user->id;

                if (!$flag = $model->save()) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Не удалось сохранить заказ");
                    return $this->render('create', [
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                        'sizes_in_cart' => $sizes_in_cart,
                    ]);
                }

                foreach ($dataProvider->getModels() as $id_size => $productSize) {
                    $orderItem = new OrderItems();

                    $orderItem->setAttributes([
                        'oi_order_id' => $model->id,
                        'oi_product_size_id' => $id_size,
                        'oi_shop_id' => $productSize->psProduct->product_shop_id,
                        'oi_status' => 1,
                        'oi_price' => $productSize->psProduct->currentPrice,
                        'oi_qty_item' => (new Cart())->getQtyProductsInCart($id_size, $sizes_in_cart),
                    ]);

                    if (!$flag = $orderItem->save()) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Не удалось добавить позиции в заказ");
                        return $this->render('create', [
                            'model' => $model,
                            'dataProvider' => $dataProvider,
                            'sizes_in_cart' => $sizes_in_cart,
                        ]);
                    }
                }

                if ($flag) {
                    $user->sendOrderInfoMessage('order-info', $model, $orderItem);
                    $transaction->commit();
                    $message[0] = "Заказ оформлен, ожидайте звонка менеджера";
                    Yii::$app->session->setFlash('success', implode('<br>', $message));

                    return $this->redirect(['index']);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', "Не удалось добавить товар. {$e->getMessage()}");
            }
        }

        if ($index = (int)$model->order_address_locality) {
            try {
                $objectId = 27020; // Письмо с объявленной ценностью
                // Минимальный набор параметров для расчета стоимости отправления
                $params = [
                    'w' => 2000, // Вес в граммах
                    'oc' => 5200, // Сумма объявленной ценности в копейках
                    'pack' => 10, // Сумма объявленной ценности в копейках
                    'from' => 640000, // Почтовый индекс места отправления
                    'to' => $index // Почтовый индекс места отправления
                ];

                // Список ID дополнительных услуг
                // 2 - Заказное уведомление о вручении
                // 21 - СМС-уведомление о вручении
                $services = [2, 21];

                $TariffCalculation = new \LapayGroup\RussianPost\TariffCalculation();
                $calcInfo = $TariffCalculation->calculate($objectId, $params, $services);
            } catch (\LapayGroup\RussianPost\Exceptions\RussianPostTarrificatorException $e) {
                // Обработка ошибок тарификатора
                // Массив вида [['msg' => 'текст ошибки', 'code' => код ошибки]]
                dd($errors = $e->getErrors(), 1);
            }

//        dd($calcInfo->getPayNds());
        }
        return $this->render('create', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'sizes_in_cart' => $sizes_in_cart,
            'calcInfo' => $calcInfo,
        ]);
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
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
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @return array
     */
    public function actionSearchPostcode($q = null): array
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => [['text' => 'Введите полный адрес проживания, включая номер дома и квартиры']]];

        if ($q !== null) {
            $token = "38e30d8acef6f0b79a87828b2d97cde54dc7a5e2";
            $dadata = new \Dadata\DadataClient($token, null);
            $addresses = $dadata->suggest("address", $q);

            if (isset($addresses)) {
                foreach ($addresses as $id => $address) {
                    $result[] = [
                        'id' => $address['unrestricted_value'],
                        'text' => $address['unrestricted_value']
                    ];
                }

                $out['results'] = $result;
            }
        }

        if (!$out['results']) {
            $out = ['results' => [['text' => 'Ничего не найдено']]];
            return $out;
        }

        return $out;
    }

    /**
     * @return array
     */
    public function actionSearchLocality($q = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => [['text' => 'Начните ввод с "Почтового индекса" или с "Названия населенного пункта".']]];

        $result = [];

        if ($q !== null) {
            $token = "38e30d8acef6f0b79a87828b2d97cde54dc7a5e2";
            $dadata = new \Dadata\DadataClient($token, null);
            $addresses = $dadata->suggest("address", $q, 10000);

            if (isset($addresses)) {
                foreach ($addresses as $id => $address) {
                    if (!empty($address['data']['postal_code'])) {
                        $addressList = [
                            'postal_code' => $address['data']['postal_code'] ?? false,
                            'region_with_type' => $address['data']['region_with_type'] ?? false,
                            'city_with_type' => $address['data']['city_with_type'] ?? false,
                            'city_district_type' => $address['data']['city_district_type'] ?? false,
                            'settlement_with_type' => $address['data']['settlement_with_type'] ?? false,
                        ];
                        $text = implode(', ', array_filter($addressList));
                        $result[$text] = [
                            'id' => $text,
                            'text' => $text
                        ];
                    }

                }

                $out['results'] = $this->unique_multidim_array($result, 'text');
            }
        }
        if (!$out['results']) {
            $out = ['results' => [['text' => 'Ничего не найдено']]];
            return $out;
        }
        return $out;
    }

    function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    /**
     * @param null $q
     * @param $locality
     * @return \array[][]|\string[][][]
     */
    public function actionSearchStreet($q = null, $locality = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!$locality) {
            $out = ['results' => [['text' => Yii::t('app', 'Вы не указали населенный пункт')]]];
            return $out;
        }

        $out = ['results' => [['text' => 'Введите название улицы']]];

        $result = [];
        $token = "38e30d8acef6f0b79a87828b2d97cde54dc7a5e2";
        $dadata = new \Dadata\DadataClient($token, null);

        if ($q !== null) {
            $addresses = $dadata->suggest("address", "{$locality}, {$q}");

            if (isset($addresses)) {
                foreach ($addresses as $id => $address) {
                    if (!empty($address['data']['street_with_type'])) {
                        $result[] = [
                            'id' => $address['data']['street_with_type'],
                            'text' => $address['data']['street_with_type']
                        ];
                    }
                }

                $out['results'] = $this->unique_multidim_array($result, 'text');
            }

            if (!$out['results']) {
                $out = ['results' => [['text' => 'Ничего не найдено. Может Вы ввели не тот "Почтовый индекс"?']]];
                return $out;
            }
        }


        return $out;
    }

    /**
     * @param null $q
     * @param $locality
     * @return \array[][]|\string[][][]
     */
    public function actionSearchHouseNumber($q = null, $locality = null, $street = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!$street) {
            $out = ['results' => [['text' => Yii::t('app', 'Вы не указали улицу')]]];
            return $out;
        }

        $out = ['results' => [['text' => 'Введите название улицы']]];

        $result = [];
        $token = "38e30d8acef6f0b79a87828b2d97cde54dc7a5e2";
        $dadata = new \Dadata\DadataClient($token, null);

        if ($q !== null) {
            $addresses = $dadata->suggest("address", "{$locality}, {$street}, {$q}");
        } else {
            $addresses = $dadata->suggest("address", "{$locality}, {$street}", 50);
        }

        if (isset($addresses)) {
            foreach ($addresses as $id => $address) {
                $addressList = [
                    'house_type' => $address['data']['house_type'] ?? false,
                    'house' => $address['data']['house'] ?? false,
                    'block_type' => $address['data']['block_type'] ?? false,
                    'block' => $address['data']['block'] ?? false,
                ];
                $text = implode('', array_filter($addressList));
                $result[$text] = [
                    'id' => $text,
                    'text' => $text
                ];
            }

            $out['results'] = $this->unique_multidim_array($result, 'text');
        }

        if (!$out['results']) {
            $out = ['results' => [['text' => 'Ничего не найдено']]];
            return $out;
        }

        return $out;
    }

    /**
     * @param null $postCode
     * @return string
     * @throws \Exception
     */
    public function actionFindCdekList($locality = null)
    {
        $model = new Orders();
        $postCode = $str = strstr($locality, ",", true);
        $client = new \Symfony\Component\HttpClient\Psr18Client();
        $cdek = new \app\base\Client($client, '47oeLu5ASl7LkmD6hy3tC5bfd3SE6jde', 'j9ACiuDQR8BwsVv6PBdugo1nBZYT9OZu');
        $pvzlist = false;
        try {
            $cdek->authorize();
            $cdek->getToken();
            $cdek->getExpire();
        } catch (\CdekSDK2\Exceptions\AuthException $exception) {
            //Авторизация не выполнена, не верные account и secure
            echo $exception->getMessage();
        }
        $result = $cdek->offices()->getFiltered(['postal_code' => $postCode]);
        if ($result->isOk()) {
            //Запрос успешно выполнился
            $pvzlist = $cdek->formatResponseList($result, \CdekSDK2\Dto\PickupPointList::class);
//            foreach ($pvzlist->items as $pvz) {
//                $pvz->code;
//                $pvz->location->address;
//                $pvz->location->address_full;
//            }
        }

        return $this->renderAjax(
            '_cdek_list', [
            'pvzlist' => $pvzlist,
            'model' => $model,
        ]);
    }

    /**
     * Оформление заказа в один клик
     * @throws NotFoundHttpException
     */
    function actionCreateOneClick()
    {

        $order = new Orders(['scenario' => \app\models\Orders::SCENARIO_CREATE_ORDER_IN_ONE_CLICK]);
        $order->setAttributes([
            'order_username' => $_GET['Orders']['order_username'],
            'order_phone' => $_GET['Orders']['order_phone']
        ]);

        if ($order->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $productsModel = (new ProductsSearch())->searchProductSizes($_GET['Products']);
                if (isset($productsModel->productSizes[$_GET['ProductSizes']['id']]) && $order->save()) {
                    $orderItems = new OrderItems();
                    $orderItems->setAttributes([
                        'oi_product_size_id' => $productsModel->productSizes[$_GET['ProductSizes']['id']]->id,
                        'oi_shop_id' => $productsModel->product_shop_id,
                        'oi_price' => $productsModel->currentPrice,
                        'product_discount_id' => $productsModel->product_discount,
                        'oi_qty_item' => 1
                    ]);

                    $order->link('orderItems', $orderItems);

                    $transaction->commit();
                    return $this->renderAjax('successful_order_processing', []);
                }

                $transaction->rollBack();

                return 0;
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    /**
     * @param $product_alias
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    function actionCreateOrderOneClick($product_alias)
    {
        $productsModel = (new ProductsSearch())->searchProductInfoForOrderOneClick($product_alias);

        // Указываем сценарий, при котором обязательными являются только поля - "Имя" и "Телефон"
        $orderModel = new Orders(['scenario' => \app\models\Orders::SCENARIO_CREATE_ORDER_IN_ONE_CLICK]);
        $productSizesModel = (new \app\models\ProductSizes());
        $userParamId = (new Userparams())->getUserParamId();
        $productSizesModel->load(post());

        if ($orderModel->load(post()) && post('button')) {
            if (!$productSizesModel->id) {
                Yii::$app->session->setFlash('error', 'Вы не выбрали размер');
            } else if ($orderModel->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$user = (new \app\models\User)::findByPhone($orderModel->order_phone)) {
                        $user = new \app\models\User(['scenario' => (new \app\models\User)::SCENARIO_CREATE_USER_IN_ONE_CLICK]);

                        $user->setAttributes([
                            'username' => $orderModel->order_username,
                            'phone' => $orderModel->order_phone,
                            'status' => $user::STATUS_NOT_ACTIVE,
                        ]);

                        $user->setPassword(generate_password(10));
                        $user->generateAuthKey();

                        if (!$user->save(false)) {
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось оформить заказ. Попробуйте перезагрузить страницу.'));
                            $transaction->rollBack();
                        }
                    }

                    $orderModel->order_user_id = $user->id;
                    $orderModel->validate();

                    if (isset($productsModel->productSizes[$productSizesModel->id]) && $orderModel->save(false)) {
                        $orderItems = new OrderItems();
                        $orderItems->setAttributes([
                            'oi_product_size_id' => $productsModel->productSizes[$productSizesModel->id]->id,
                            'oi_shop_id' => $productsModel->product_shop_id,
                            'oi_price' => $productsModel->currentPrice,
                            'product_discount_id' => $productsModel->product_discount,
                            'oi_qty_item' => 1
                        ]);

                        $orderModel->link('orderItems', $orderItems);

                        $orderModel->sendOrderLetterInOneClickToAdmin('order-one-click-info', $productsModel, $orderModel);

                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Заказ успешно оформлен. Ожидайте звонка менеджера.'));
                    }
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось оформить заказ. Попробуйте перезагрузить страницу.'));

                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось оформить заказ. Попробуйте перезагрузить страницу.'));

                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create-one-click', [
                'productsModel' => $productsModel,
                'orderModel' => $orderModel,
                'productSizesModel' => $productSizesModel,
                'userParamId' => $userParamId,
            ]);
        }

        return $this->render('create-one-click', [
            'productsModel' => $productsModel,
            'orderModel' => $orderModel,
            'productSizesModel' => $productSizesModel,
            'userParamId' => $userParamId,
        ]);
    }
}
