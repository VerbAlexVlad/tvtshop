<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\Fancybox;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Список товаров');
$this->params['breadcrumbs'][] = $this->title;
$js = <<< JS
    // $(document).ready(function() {
    //     var rowCount = $('#dataTables-example >tbody >tr >td').length;
    //
    //     if(rowCount > 1) {
    //         $('#dataTables-example').DataTable({
    //             bAutoWidth: true,
    //             responsive: true,
    //             paging: false,
    //             searching: false,
    //             ordering:  false,
    //             info: false,
    //             sDom: ''
    //         });
    //     }
    // });
JS;
$this->registerJs($js, $position = yii\web\View::POS_END, $key = null);
?>
<h1 class="page-header"><?= Html::encode($this->title) ?></h1>

<div class="products-index">
    <p>
        <?= Html::a(Yii::t('app', '+ Добавить товар'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="panel-body">
            <?php try {
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-striped table-bordered', 'id' => 'dataTables-example', 'width' => "auto"],
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'width: 100px !important;'],
                            'value' => function ($data) {
                                return $this->render('_product_basic_info', ['data' => $data]);
                            }
                        ],
                        [
                            'attribute' => 'product_price',
                            'format' => 'raw',
                            'value' => function ($data) {
                                $currentPrice = $data->currentPrice;
                                $result = Html::tag('div', Yii::$app->formatter->asDecimal($currentPrice));

                                if ($currentPrice !== $data->product_price) {
                                    $result .= Html::tag('del', Yii::$app->formatter->asDecimal($data->product_price));
                                }
                                return $result;
                            }
                        ],
                        [
                            'attribute' => 'product_status',
                            'format' => 'raw',
                            'filter' => \kartik\select2\Select2::widget(
                                [
                                    'attribute' => 'product_status',
                                    'model' => $searchModel,
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                    'data' => [1 => 'В наличии', 0 => 'Отсутствует'],
                                    'hideSearch' => false,
                                    'options' => [
                                        'placeholder' => 'Выберите...',
                                    ],
                                ]),
                            'value' => function ($data) {
                                return $data->productSvailability;
                            }
                        ],
                        [
                            'attribute' => 'product_discount',
                            'format' => 'raw',
                            'value' => function ($data) {
                                return $data->discountValue;
                            }
                        ],
                        'product_price_wholesale',
                        [
                            'attribute' => 'product_floor',
                            'value' => function ($data) {
                                return !empty($data->floor) ? $data->floor->floor_name : false;
                            }
                        ],
                        [
                            'attribute' => 'product_season',
                            'value' => function ($data) {
                                return !empty($data->productsSeason) ? $data->productsSeason->season_name : false;
                            }
                        ],
                        [
                            'attribute' => 'product_model_id',
                            'value' => function ($data) {
                                return !empty($data->productModel->model_name) ? $data->productModel->model_name : false;
                            }
                        ],
                        [
                            'attribute' => 'product_colors',
                            'value' => function ($data) {
                                return $data->implodeColors ?? false;
                            }
                        ],
                        [
                            'attribute' => 'product_description_id',
                            'format' => 'raw',
                            'value' => function ($data) {
                                return !empty($data->productDescription->description_text) ? $data->productDescription->description_text : false;
                            }
                        ],
                        [
                            'attribute' => 'product_datecreate',
                            'format' => 'raw',
                            'value' => function ($data) {
                                return Yii::$app->formatter->asDate($data->product_datecreate, 'MM.dd.yyyy'); // 2014-10-06;
                            }
                        ],
                        'product_views',
                        [
                            'attribute' => 'product_datecreate',
                            'format' => 'raw',
                            'headerOptions' => ['width' => 120],
                            'value' => function ($data) {
                                $sizes = [];

                                foreach ((new app\models\ProductSizes)->getArrayProductSizes($data->productSizes) as $productSize) {
                                    $sizes[] = Fancybox::widget([
                                        "fancyboxClass" => "",
                                        "fancyboxText" => $productSize->size_name,
                                        "fancyboxTitle" => "Посмотреть информацию о товаре, не покидая страницы",
                                        "fancyboxUrl" => Url::to(['/admin/products/product-size-param-list', 'param_id' => $productSize->id]),
                                    ]);
                                }

                                $sizes = array_filter($sizes);

                                if (!empty($sizes)) {
                                    return "Размеры в наличии: " . implode(', ', $sizes);
                                }
                                return "Нет в наличии";
                            }
                        ],
                    ],
                ]);
            } catch (Exception $e) {
            } ?>
        </div>
    </div>

</div>