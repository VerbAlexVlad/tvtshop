<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\commands;
use app\models\Remains;
use app\modules\admin\models\Keywords;
use app\modules\admin\models\ProductModelContent;
use app\modules\admin\models\ProductsCharacteristics;
use app\modules\admin\models\DimensionRuler;
use app\modules\admin\models\ProductsKeys;
use app\modules\admin\models\readerProducts\Outmaxbiz;
use app\modules\admin\models\ProductModel;
use app\modules\admin\models\Categories;
use app\modules\admin\models\Products;
use app\modules\admin\models\Receipt;
use app\modules\admin\models\Param;
use app\modules\admin\models\Brand;
use app\modules\admin\models\OrderItems;

use yii\console\Controller;
use app\base\Model;
use Yii;


use app\models\Discount;
use yii\helpers\ArrayHelper;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class OutmaxbizController extends Controller
{

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionIndex()
    {
        ini_set('max_execution_time', 4000);
        ini_set('memory_limit', -1);

        $date_today = date("m.d.y"); //присвоено 12.03.15
        $today[1] = date("H:i:s", strtotime("+8 hours")); //присвоит 1 элементу массива 18:32:17
        echo("Интеграция началась в: {$today[1]}, {$date_today} .\n");
        $data = [];
        require('/var/www/www-root/data/www/tvtshop.ru/functions.php');
        require('/var/www/www-root/data/www/tvtshop.ru/web/simple_html_dom.php');

      
//         $p = \app\models\Shops::find()->where(['id'=>51])->one();
//       dd($p);
//         foreach($p as $item) {
//             $item->shop_name = 'Беларусь';
//             $item->save();
//         }
//         die;
      
//        // Удаляет все значения из таблицы param, которых нет в таблице dimension_ruler
//        $dimRuller = DimensionRuler::find()->select(['id', 'param_id'])->indexBy('param_id')->asArray()->all();
//
//        $param = Param::find()->all();
//
//        $i = 0;
//        foreach ($param as $item) {
//            if(!isset($dimRuller[$item->product_param_id])) {
//                $item->delete();
//                $i++;
//            }
//        }

//        // должно было удалять из таблици param все данные о не дочерних категориях
//        $categoriesInfo = \app\modules\user\models\Categories::find()
//            ->asArray()
//            ->indexBy('id')
//            ->all();
//
//        $i = 0;
//        foreach ($categoriesInfo as $categoryInfo) {
//            if($categoryInfo['lft'] != $categoryInfo['rgt']-1) {
//                $params = Param::find()->where(['category_id' => $categoryInfo['id']])->all();
//                if(!empty($params)) {
//                    foreach ($params as $param) {
//                        dd(222);
////                        $param->delete();
//                    }
//                }
//            }
//        }
//        die;

//        /** @var
//         * Добавляет в таблице param значения  low_limit и up_limit
//         */
//
//        $categoriesInfo = \app\modules\user\models\Categories::find()
//            ->with([
//                'categoriesParameters'=>function($categoriesParameters) {
//                    $categoriesParameters->indexBy('parameter_id');
//                }
//            ])
//            ->asArray()
//            ->indexBy('id')
//            ->all();
//
//        $params = Param::find()->where(['up_limit' => null])->andWhere(['low_limit' => null])->all();
//
//        $i = 1;
//        foreach ($params as $param) {
//            if(isset($categoriesInfo[$param->category_id]['categoriesParameters'][$param->parameters_id])) {
//                $param->low_limit = $categoriesInfo[$param->category_id]['categoriesParameters'][$param->parameters_id]['before'];
//                $param->up_limit = $categoriesInfo[$param->category_id]['categoriesParameters'][$param->parameters_id]['after'];
//            }
//
//dd($param);
//if(!$param->save()) {
//    dd($param->errors);
//}
//
//            echo ("$i из " . count($params) . "\n");
//            $i++;
//        }
//die;
        //
//        /**
//         * Удаляет дубли в таблице param
//         */
//
//        for($i=5000; $i>=1; $i--) {
//            $groupBy_productParam = Param::find()->where(['product_param_id' => $i])->indexBy('parameters_id')->all();
//
//            if($groupBy_productParam) {
//                $groupParam = Param::find();
//
//                foreach ($groupBy_productParam as $post_value) {
//                    $groupParam = $groupParam->orWhere(
//                        [
//                            'and',
//                            ['parameters_id' => $post_value->parameters_id],
//                            ['value' => $post_value->value],
//                            ['category_id' => $post_value->category_id],
//                        ]);
//                }
//
//                $groupParam = ArrayHelper::index($groupParam->andWhere(['!=', 'product_param_id', $i])->asArray()->orderBy(['id' => SORT_DESC])->all(), null, 'product_param_id');
//
//                foreach ($groupParam as $id=>$item) {
//                    $g = Param::find()->where(['product_param_id'=>$id])->indexBy('parameters_id')->all();
//
//                    if(count($g) == count($groupBy_productParam)) {
//
//                        foreach($groupBy_productParam as $id_groupBy=>$groupBy_pr) {
//                            if(!isset($g[$id_groupBy]) || ($groupBy_pr->value != $g[$id_groupBy]->value) || ($groupBy_pr->category_id != $g[$id_groupBy]->category_id)) {
//                                continue(2);
//                            }
//                        }
//
//                        $dimRul = \app\models\DimensionRuler::find()->where(['param_id'=>$id])->all();
//
//                        if($dimRul) {
//                            foreach ($dimRul as $dimRu) {
//                                $dimRu->param_id = $i;
//                                $dimRu->save();
//                            }
//                        }
//
//                        foreach($g as $ss) {
//                            $ss->delete();
//                        }
//
//                    }
//                }
//            }
//        }






















        $reader = new \XMLReader();
        $reader->open('http://bizoutmax.ru/price/export/4.yml'); // указываем ридеру что будем парсить этот файл
        $id = 0;
        $categories = [];
        $unset = [];
        $i = [];
        while ($reader->read()) {
            if ($reader->nodeType == \XMLReader::ELEMENT) {
                switch ($reader->localName) {
                    case 'yml_catalog':
                        break;
                    case 'shop':
                        break;
                    case 'company':
                        break;
                    case 'phone':
                        break;
                    case 'platform':
                        break;
                    case 'version':
                        break;
                    case 'currencies':
                        break;
                    case 'currency':
                        break;
                    case 'categories':
                        break;
                    case 'category':
                        break;
                    case 'offers':
                        break;
                    case 'offer':
                        break;
                    case 'url':
                        break;
                    case 'price':
                        break;
                    case 'currencyId':
                        break;
                    case 'categoryId':
                        break;
                    case 'picture':
                        break;
                    case 'delivery':
                        break;
                    case 'name':
                        break;
                    case 'vendor':
                        break;
                    case 'vendorCode':
                        break;
                    case 'model':
                        break;
                    case 'outlets':
                        break;
                    case 'param':
                        break;
                    default:
                       dd($reader->localName);
                }
            }
        }
        die;
        // циклическое чтение документа
        while ($reader->read()) {
            if ($reader->nodeType == \XMLReader::ELEMENT) {
                switch ($reader->localName) {
                    case 'category':
                        $cat_id = $reader->getAttribute('id');

                        $categories[$cat_id] = [
                            'id' => $cat_id,
                            'parentId' => $reader->getAttribute('parentId'),
                            'name' => null
                        ];

                        $reader->read();

                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $categories[$cat_id]['name'] = trim($reader->value);
                        }

                        break;
                    case 'offer':

                        if(isset($unset[$id]) && $unset[$id] == 1) unset($data[$id]);
                        if(isset($data[$id]) && !isset($data[$id]['model'])) unset($data[$id]);

                        if(isset($data[$id]['brand'])) {
                            if ($data[$id]['brand'] == 'Timberland' || $data[$id]['brand'] == 'VANS') {
                                unset($data[$id]);
                            }
                        }



                        $id = $reader->getAttribute('id');
                        $ie = null;



                        $data[$id]['dimensionRuler_id'] = $id;
                        $data[$id]['available'] = $reader->getAttribute('available');

                        break;
                    case 'name':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $i[$ie] = trim($reader->value);

                            $data[$id]['name'] = trim($reader->value);
                        }

                        break;
                    case 'model':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['model'] = $data[$id]['name'];
                        }
                        break;
                    case 'url':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['url'] = trim($reader->value);
                        }
                        break;
                    case 'price':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['price'] = trim($reader->value);
                        }
                        break;
                    case 'categoryId':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['category_id'] = Outmaxbiz::categoryId(trim($reader->value));

                            if(($data[$id]['category_id']) == false) {
                                $ie = $reader->value;
                                $i[$reader->value] = 1;
                                $unset[$id] = 1;
                            }
                        }
                        break;
                    case 'picture':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            if(!empty(trim($reader->value))) {
                                $data[$id]['picture'][] = trim($reader->value);
                            }
                        }

                        if(empty($data[$id]['picture'])) {
                            $unset[$id] = 1;
                        }
                        break;
                    case 'vendor':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['brand'] = Outmaxbiz::searchBrand(trim($reader->value));

                            if(($data[$id]['brand']) == false) {
                                $unset[$id] = 1;
                            }
                        }

                        break;
                    case 'vendorCode':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['product_id'] = trim($reader->value);
                        }
                        break;
                    case 'param':
                        $data_name = trim($reader->getAttribute('name'));

                        // читаем дальше для получения текстового элемента
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            if($data_name == 'Пол') {
                                if(isset($data[$id]['floor'])) {
                                    $data[$id]['floor'] = 0;
                                    $unset[$id] = 1;
                                } else {
                                    switch ($reader->value) {
                                        case 'Мужской':
                                            $data[$id]['floor'] = 1;
                                            break;
                                        case 'Женский':
                                            $data[$id]['floor'] = 2;
                                            break;
                                        default:
                                            $unset[$id] = 1;
                                    }
                                }
                            } elseif ($data_name == 'Размер обуви') {
                                $data[$id]['size'] = Outmaxbiz::searchFootSize($reader->value);
                                $data[$id]['sizeOn'] = 'обувь';
                            } elseif ($data_name == 'Размер детской одежды') {
                                $unset[$id] = 1;
                            } elseif ($data_name == 'Цвет') {
                                $data[$id]['color'] = Outmaxbiz::searchColor($reader->value);

                                if($data[$id]['color'] == false) {
                                    $unset[$id] = 1;
                                }
                            }  elseif ($data_name == 'Сезон') {
                                $data[$id]['season'] = Outmaxbiz::searchSeason($reader->value);
                            } elseif ($data_name == 'Размер одежды') {
                                switch ($data[$id]['category_id']) {
                                    case 100:
                                    case 155:
                                    case 135:
                                    case 136:
                                    case 157:
                                    case 125:
                                    case 152:
                                        $data[$id]['size'] = Outmaxbiz::searchPantsSize($reader->value);
                                        $data[$id]['sizeOn'] = 'штаны';
                                        break;
                                    case 94:
                                    case 150:
                                    case 133:
                                    case 90:
                                    case 138:
                                    case 151:
                                    case 161:
                                    case 165:
                                    case 166:
                                    case 163:
                                    case 130:
                                    case 132:
                                    case 89:
                                    case 160:
                                    case 129:
                                    case 134:
                                        $data[$id]['size'] = Outmaxbiz::searchClothesSize($reader->value);
                                        $data[$id]['sizeOn'] = 'одежда';
                                        break;
                                    default:
                                        $unset[$id] = 1;
                                }
                            } else {
                                $data[$id]['characteristics'][$data_name] = $reader->value;

                                if($data_name == 'Категория' && $data[$id]['category_id'] == 114) {
                                    switch ($data[$id]['characteristics'][$data_name]) {
                                        case "Кроссовки с мехом":
                                            $data[$id]['category_id'] = 158;
                                            break;
                                        case "Баскетбол":
                                            $data[$id]['category_id'] = 159;
                                            break;
                                        case "Бег":
                                            $data[$id]['category_id'] = 124;
                                            break;
                                        case "Ботинки":
                                            $data[$id]['category_id'] = 117;
                                            break;
                                        case "Скейтборд":
                                            $data[$id]['category_id'] = 116;
                                            break;
                                        case "Футбол":
                                            $data[$id]['category_id'] = 120;
                                            break;
                                        case "Сапоги":
                                            $data[$id]['category_id'] = 118;
                                            break;
                                    }
                                } elseif ($data[$id]['category_id'] == 157) {
                                    if((preg_match_all("/(.*?)Брюки спортивные(.*)/iu", $data[$id]['name']))) {
                                        $data[$id]['category_id'] = 136;
                                    } elseif ((preg_match_all("/(.*?)Джоггеры(.*)/iu", $data[$id]['name']))) {
                                        $data[$id]['category_id'] = 125;
                                    } else {
                                        $unset[$id] = 1;
                                    }
                                }
                            }
                        }

                        break;
                }
            }
        }
        if(isset($unset[$id])) unset($data[$id]);

        if (isset($data[0])) {
            unset($data[0]);
        }


        $result = [];
        foreach ($data as $i => $items) {
            if(!isset($items['size'])) {
                $data[$i]['size'] = 59;
                $items['size'] = 59;
            };

            if (isset($items['sizeOn']) && $items['sizeOn'] == 'обувь') {
                if(!isset($items['floor'])) {
                    $data[$i]['param'] = Outmaxbiz::size_obuv_man($items['size']);
                } elseif ($items['floor'] == 2) {
                    $data[$i]['param'] = Outmaxbiz::size_obuv_wom($items['size']);
                } else {
                    $data[$i]['param'] = Outmaxbiz::size_obuv_man($items['size']);
                }
            } elseif (isset($items['sizeOn']) && $items['sizeOn'] == 'одежда') {
                if(!isset($items['floor'])) {
                    $data[$i]['param'] = Outmaxbiz::GTB($items['size']);
                } elseif ($items['floor'] == 2) {
                    $data[$i]['param'] = Outmaxbiz::GTB_wom($items['size']);
                } else {
                    $data[$i]['param'] = Outmaxbiz::GTB($items['size']);
                }
            } elseif (isset($items['sizeOn']) && $items['sizeOn'] == 'штаны') {
                if(!isset($items['floor'])) {
                    $data[$i]['param'] = Outmaxbiz::TB($items['size']);
                } elseif ($items['floor'] == 2) {
                    $data[$i]['param'] = Outmaxbiz::TB_wom($items['size']);
                } else {
                    $data[$i]['param'] = Outmaxbiz::TB($items['size']);
                }
            }

            if (isset($data[$i]['param']) && $data[$i]['param'] == false) continue;

            if (!isset($result[$items['product_id']])) {
                $productsCharacteristics = [];

                foreach ($items['characteristics'] as $itemId=>$itemName) {
                    $characteristic_id = false;
                    switch ($itemId) {
                        case 'Материал':
                            $characteristic_id = 1;
                            break;
                        case 'Материал подошвы':
                            $characteristic_id = 22;
                            break;
                        case 'Материал верха':
                            $characteristic_id = 23;
                            break;
                        case 'Страна бренда':
                            $characteristic_id = 19;
                            break;
                    }

                    if(!empty($characteristic_id)) {
                        $productsCharacteristics[] = [
                            'characteristic_id' => $characteristic_id,
                            'volume' => $itemName
                        ];
                    }

                }
              
                $price = 0;
              
                switch (true) {
                    case ($items['price'] < 100):
                        $price = $items['price'] + 300;
                        break;
                    case ($items['price'] < 500):
                        $price = $items['price'] + 400;
                        break;
                    case ($items['price'] < 1000):
                        $price = $items['price'] + 500;
                        break;
                    case ($items['price'] < 1500):
                        $price = $items['price'] + 1000;
                        break;
                    case ($items['price'] < 2000):
                        $price = $items['price'] + 1100;
                        break;
                    case ($items['price'] < 2500):
                        $price = $items['price'] + 1200;
                        break;
                    case ($items['price'] < 3000):
                        $price = $items['price'] + 1300;
                        break;
                    case ($items['price'] < 3500):
                        $price = $items['price'] + 1400;
                        break;
                    default:
                        $price = $items['price'] + 1500;
                        break;
                }
              
              
                $result[$items['product_id']] = [
                    'ProductModel'        => [
                        'category_id' => $items['category_id'],
                        'brand'       => isset($items['brand']) ? $items['brand'] : 47,
                        'model'       => $items['model']
                    ],
                    'ProductModelContent' => [
                        'content' => ['']
                    ],
                    'Products'            => [
                        'shops_id'        => 47,
                        'gallery'         => [
                            isset($items['picture']) ? $items['picture'] : null
                        ],
                        'floor'           => (isset($items['floor'])) ? $items['floor'] : 0,
                        'season'          => isset($items['season']) ? $items['season'] : 3,
                        'products_url'    => [$items['url']],
                        'price'           => $price,
                        'discount'           => 1,
                        'price_wholesale' => $items['price'],
                        'color'           => [isset($items['color']) ? $items['color'] : null],
                        'id_internal'     => [$items['product_id']]
                    ],

                ];

                if(!empty($productsCharacteristics)) {
                    $result[$items['product_id']]['ProductsCharacteristics'] = $productsCharacteristics;
                }
            }

            $result[$items['product_id']]['DimensionRuler'][] = [
                'size'        => $items['size'],
                'id_internal' => $items['dimensionRuler_id']
            ];
            $result[$items['product_id']]['DimensionRulerAvailable'][] = $items['available'];
            $result[$items['product_id']]['Receipt'][] = [
                'count'       => 500,
                'provider_id' => 4,
            ];
            $result[$items['product_id']]['Param'][] = isset($data[$i]['param']) ? $data[$i]['param'] : null;

        }
      

      $product = Products::find()
               ->where(['shops_id' => 47])
               ->indexBy('id_internal')
               ->all();
      
//       $i=0;
//        foreach ($result as $id_internal=>$item) {
//          try {
// //            if(isset($product[$id_internal])) {
// //                $product_html = curl_get($item['Products']['products_url'][0]);
// //                $product_dom = str_get_html($product_html);
// //                if (!empty($product_dom)) {
// //                    $products_content = $product_dom->find('div.tab-content', 0)->innertext;
// //                } else {
// //                    $products_content = '';
// //                }

// //                if(!empty($products_content)) {
// //                   $product_model_content = '';
// //                   $product_model_content = ProductModelContent::createContent($product[$id_internal]->model_id, $products_content);

// //                   if ($product_model_content != false) {
// //                       if (!($flag = $product_model_content->save())) {
// //                           $transaction->rollBack();
// //                           echo "Ошибка в описании \n";
// //                           continue;
// //                       }
// //                   }
                 
// //                  $product[$id_internal]->content = isset($product_model_content->id) ? $product_model_content->id : null;
                 
// //                  $product[$id_internal]->save();
// //                  echo "Изменено {$i} описание\n";
// //                  $i++;
// //                }
// //            }
         
//           if(!isset($product[$id_internal])) {
//                $product_html = curl_get($item['Products']['products_url'][0]);
//                $product_dom = str_get_html($product_html);
//                if (!empty($product_dom)) {
//                    $products_content = $product_dom->find('div.tab-content', 0)->innertext;
//                } else {
//                    $products_content = '';
//                }

//                if(!empty($products_content)) {
//                  $result[$id_internal]['ProductModelContent']['content'][0] = $products_content;
//                }
//            }
       
//         } catch (\Exception $e) {
//             echo $e->getMessage()."\n";
//         }
//       }
        $this->integratsiya_s_saytom($result, 47);

        // Проходит по всем товарам, и смотрит есть ли у него размеры, если нет, status=0
        $p = Products::find()
            ->where(['shops_id' => 47])
            ->with([
                'dimRuller' => function ($dimRuller) {
                    $dimRuller->with(['dimRulRemains', 'receipt']);
                }
            ])
            ->indexBy('id_internal')
            ->all();
      
        foreach ($p as $product) {
           static $no_product=0;
           static $is_product=0;
           static $del_product=0;
          
           if(!isset($result[$product->id_internal])) {
               // если у товара есть размеры, проходим по ним, если нет, удаляем товар
               if(!empty($product->dimRuller)) {
                  if($product->status == 1) {
                       $product->status = 0;
                       $product->save();
                       foreach ($product->dimRuller as $size) {
                           if(!empty($size->dimRulRemains)) {
                               // заносим в переменную остаток по данному размеру
                               $remains_count = $size->dimRulRemains->remains;
                               // если остаток больше нуля, то убираем остаток
                               if($remains_count > 0) {
                                   foreach($size->receipt as $receipt){
                                       if($receipt->count > $remains_count) {
                                           $receipt->count -= $remains_count;
                                           $receipt->save();
                                           break;
                                       } else {
                                           $remains_count -= $receipt->count;
                                           $receipt->delete();
                                       }
                                   }
                               }
                               $remains_count = null;
                           }
                         
                            $size->status = 0;
                            $size->save();
                       }
                        $no_product++;
                  }
               } else {
//                     $product->delete();
                    $product->status = 0;
                    $product->save();
                 
                    $del_product++;
               }
                continue;
           }
          
            $count = 0;
            foreach ($product->dimRuller as $dimRuller) {
                if (empty($dimRuller->dimRulRemains) || $dimRuller->dimRulRemains->remains == 0) {
                    if($dimRuller->status != 0) {
                        $dimRuller->status = 0;
                        $dimRuller->save();
                    }
                } elseif ($dimRuller->dimRulRemains->remains > 0) {
                    if($dimRuller->status != 1) {

                        $dimRuller->status = 1;
                        $dimRuller->save();
                    }
                    $count++;
                }
            }
          
            if (($count == 0) && $product->status == 1) {
                $no_product++;
                $product->status = 0;
                $product->save();
            } elseif (($count > 0) && $product->status == 0) {
                $is_product++;
                $product->status = 1;
                $product->save();
            }
        }
      
      
        DimensionRuler::findAllForFilter();
      
        echo "- Статус 0 поставлен {$no_product} товарам\n";
        echo "- Статус 1 поставлен {$is_product} товарам\n";
        echo "- Удалено {$del_product} товаров\n";

        $date_today = date("m.d.y"); //присвоено 12.03.15
        $today[1] = date("H:i:s", strtotime("+8 hours")); //присвоит 1 элементу массива 18:32:17
        echo("Интеграция закончилась в: {$today[1]}, {$date_today} \n");
    }

    public function integratsiya_s_saytom($data, $shop_id) {
        // находим все продукты данного продавца
        $allProducts = Products::find()
            ->with(['model', 'dimRuller'])
            ->where(['shops_id' => $shop_id])
            ->indexBy('id_internal')
            ->all();
        $allDimRuller = DimensionRuler::find()
            ->select(['dimension_ruler.id', 'dimension_ruler.id_internal', 'dimension_ruler.product_id', 'dimension_ruler.size'])
            ->joinWith(
                [
                    'products' => function($products) use ($shop_id) {
                        $products->andWhere(['products.shops_id' => $shop_id]);
                    },
                    'productsCount' => function($remains) {
                        $remains->select(['products_count.dimension_ruler_id', 'products_count.remains']);
                    },
                    'receipt' => function($receipt) {
                        $receipt->select(['receipt.id', 'receipt.dimension_ruler_id', 'receipt.count']);
                    },
                ])
            ->indexBy('id_internal')
            ->all();
        $address = 0;
        $date_update = 0;
        $price = 0;
        foreach ($data as $prod_id=>$data_item) {
            if(empty($data_item['Products']['products_url'])) continue;

            $available = false;
            foreach ($data_item['DimensionRulerAvailable'] as $dimRul) {
                if($dimRul == true) $available = true;
            }

            if(!isset($allProducts[$prod_id])) {
                if($available == false) continue;

                $brand = Brand::find()->where(['name' => $data_item['ProductModel']['brand']])->one();
                if(empty($brand)) {
                    echo 'Неизвестный бренд: ' . $data_item['ProductModel']['brand'] . "\n";
                    continue;
                }
                $product_model = ProductModel::find()
                    ->where(['like', 'model', $data_item['ProductModel']['model']])
                    ->andWhere(['category_id' => $data_item['ProductModel']['category_id']])
                    ->andWhere(['brand' => $brand->id])
                    ->one();
                if(empty($product_model)) {
                    $product_model = new ProductModel();
                    $product_model->model=$data_item['ProductModel']['model'];
                    $product_model->category_id=$data_item['ProductModel']['category_id'];
                    $product_model->brand = $brand->id;
                    if($product_model->save() == false) {
                        continue;
                    };
                }
                try {
                    $transaction = \Yii::$app->db->beginTransaction();
                    $category = Categories::findOne($product_model->category_id);
                    $flag = false;
                  
                  
                    foreach ($data_item['Products']['products_url'] as $id=>$item) {
                        $product_model_content = '';
                        if(!empty($data_item['ProductModelContent']['content'][$id])) {
                            $product_model_content = ProductModelContent::createContent($product_model->id, $data_item['ProductModelContent']['content'][$id]);

                            if ($product_model_content != false) {
                                if (!($flag = $product_model_content->save())) {
                                    $transaction->rollBack();
                                    echo "Ошибка в описании \n";
                                    continue;
                                }
                            }
                        }

                        $color = isset($data_item['Products']['color'][$id]) ? $data_item['Products']['color'][$id] : null;

                        $products = Products::createProductObject($product_model, $product_model_content, $data_item, $id, $color, $shop_id);
                        if (empty($flag = $products->save())) {
                            $transaction->rollBack();
                            echo "Ошибка при сохранении продукта \n";
                            continue;
                        }
                      
                        $products = Products::productAlias($products, $product_model->model, $category);

                        if(empty($products->save())) {
                            $transaction->rollBack();
                            throw new \InvalidArgumentException('Не удалось сохранить товар c новым alias');
                        }

                        $keywords = Keywords::generationKey($floor = $products->floor, $category_id = $products->category_id, $brand_id = $products->brand_id);

                        foreach($keywords as $keyword) {
                            $key = Keywords::SearchOrCreateKey($keyword);
                            if (!$key) {
                                throw new \InvalidArgumentException('Не удалось сохранить ключевые слова 1');
                            }

                            $product_key = ProductsKeys::create($products->id, $key->id);
                            if (!$product_key) {
                                throw new \InvalidArgumentException('Не удалось сохранить ключевые слова');
                            }
                        }

                        if(isset($data_item['ProductsCharacteristics']) && !empty($data_item['ProductsCharacteristics'])) {
                            // Сохранение характеристик продукта
                            $products_characteristics = Model::createNotPostMultiple(ProductsCharacteristics::class, $data_item);

                            Model::loadMultiple($products_characteristics, $data_item);
                            $valid = Model::validateMultiple($products_characteristics);

                            if (isset($valid)) {
                                // Сохраняем характеристики товара
                                foreach ($products_characteristics as $products_characteristics_item) {
                                    if (!empty($products_characteristics_item)) {
                                        $products_characteristics_item->product_id = $products->id;
                                        if (empty($flag = $products_characteristics_item->save())) {
                                            $transaction->rollBack();
                                            dd($products_characteristics_item->errors);
                                        }
                                    }
                                }
                            }
                        }
                        if ($data_item['Products']['gallery'][$id]) {
                            $products->gallery = $data_item['Products']['gallery'][$id];
                            if (!($flag = $products->uploadGalleryOnUrl('/var/www/www-root/data/www/tvtshop.ru/web/'))) {
                                $transaction->rollBack();
                                echo "Не удалось сохранить картинки \n"; continue;
                            }
                            unset ($products->gallery);
                        }
                        // Сохраняем размерную линейку
                        if(isset($data_item['DimensionRuler']) && !empty($data_item['DimensionRuler'])) {
                            $dimensionruler = Model::createNotPostMultiple(DimensionRuler::class, $data_item);
                            Model::loadMultiple($dimensionruler, $data_item);
                            $valid = Model::validateMultiple($dimensionruler);
                            if ($valid) {
                                foreach ($dimensionruler as $dimensionruler_id => $dimensionruler_item) {
                                    if(($data_item['DimensionRulerAvailable'][$dimensionruler_id] == 'true') && $data_item['DimensionRuler'][$dimensionruler_id]['size'] == 59) {
                                        // Ищем в базе, строку где точно такие же параметры одежды, какие указали при добавлении размеров
                                        $dimensionruler_item->product_id = $products->id;
                                        // Если такой параметра уже существует в базе, то просто сохраняем или изменяем эту размерную линейку, с param = $item->param_id
                                        if (($flag = $dimensionruler_item->save()) == false) {
                                            echo "Ошибка в $dimensionruler_item \n";
                                            continue;
                                        }
                                        $receipt = new Receipt();
                                        $receipt->dimension_ruler_id = $dimensionruler_item->id;
                                        $receipt->count = $data_item['Receipt'][$dimensionruler_id]['count'];
                                        $receipt->provider_id = $data_item['Receipt'][$dimensionruler_id]['provider_id'];
                                        if (!($flag = $receipt->save(false))) {
                                            echo "Ошибка в receipt \n";
                                            continue;
                                        }
                                    } elseif (($data_item['DimensionRulerAvailable'][$dimensionruler_id] == 'true') && !empty($data_item['Param'][$dimensionruler_id])) {
                                        // Ищем в базе, строку где точно такие же параметры одежды, какие указали при добавлении размеров
                                        $dimensionruler_item = Param::searchEnteredParameters($dimensionruler_id, $product_model->category_id, $dimensionruler_item, $data_item['Param'][$dimensionruler_id]['value']);
                                        $dimensionruler_item->product_id = $products->id;
                                        // Если такой параметра уже существует в базе, то просто сохраняем или изменяем эту размерную линейку, с param = $item->param_id
                                        if (isset($dimensionruler_item->param_id)) {
                                            if (($flag = $dimensionruler_item->save()) == false) {
                                                echo "Ошибка в $dimensionruler_item \n";
                                                continue;
                                            }
                                        } else { // Если такого параметра нет в базе
                                            // Сохраняем новые параметры в базу
                                            if (!$dimensionruler_item = Param::savingEnteredParameters($dimensionruler_item, $data_item['Param'][$dimensionruler_id]['value'], $product_model->category_id)) {
                                                $transaction->rollBack();
                                                echo "Ошибка в dimensionruler_item_2 \n";

                                                continue;
                                            }
                                            // Сохраняем данную размерную линейку
                                            if (!$flag = $dimensionruler_item->save()) {
                                                echo "Ошибка в dimensionruler_item_3 \n";
                                                continue;
                                            }
                                            // Ищем всех пользователей, для которых введеные параметры точьВточь
                                            $search_users = Param::SearchUsersWithEnteredParameters($data_item['Param'][$dimensionruler_id]['value'], $product_model->category_id);
                                            // Если такие пользователи найдены, то в таблицу paramid сохраняем данный параметр для данного пользователя
                                            if ($search_users) {
                                                $flag = Param::SavingEnteredParametersForUsers($search_users, $dimensionruler_item->param_id, $product_model->category_id);
                                                if (!$flag) {
                                                    echo "Ошибка в search_users \n";
                                                    continue;
                                                }
                                            }
                                        }
                                        $receipt = new Receipt();
                                        $receipt->dimension_ruler_id = $dimensionruler_item->id;
                                        $receipt->count = $data_item['Receipt'][$dimensionruler_id]['count'];
                                        $receipt->provider_id = $data_item['Receipt'][$dimensionruler_id]['provider_id'];
                                        if (!($flag = $receipt->save(false))) {
                                            echo "Ошибка в receipt \n";
                                            continue;
                                        }
                                    }
                                }
                            }
                        }
                    }
                  
                    if ($flag == true) {
                        $transaction->commit();
                        echo "Новый товар {$data_item['Products']['products_url'][0]}\n";
                    } else {
                        $transaction->rollBack();
                        echo "что-то пошло не так \n";
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    echo $e->getMessage()."\n";
                }
            } else {
                $update = false;
                //// ВОТ ТУТ ЖОПА, НАДО СМОТРЕТЬ ВНИМАТЕЛЬНО ////
                if($allProducts[$prod_id]->products_url != $data_item['Products']['products_url'][0]) {
                    $allProducts[$prod_id]->products_url = $data_item['Products']['products_url'][0];
                    $update = true;
                    $address += 1;
                }
                if($allProducts[$prod_id]->price != $data_item['Products']['price']) {
                    $allProducts[$prod_id]->price = $data_item['Products']['price'];
                    $update = true;
                    $price += 1;
                }

                if($allProducts[$prod_id]->price_wholesale != $data_item['Products']['price_wholesale']) {
                    $allProducts[$prod_id]->price_wholesale = $data_item['Products']['price_wholesale'];
                    $update = true;
                    $price += 1;
                }
              
                if($update == true) {
                    $allProducts[$prod_id]->date_of_creation = date("Y-m-d");
                    $allProducts[$prod_id]->save();
                }

                // если продукт уже добавлен в базу данных (products), мы смотрим, какие размеры есть на сайте firebox.
                foreach ($data_item['DimensionRuler'] as $dimRul_id=>$dimRul_item) {
                    static $avialable = false;
                    // Ищем размер соответствующий внутреннему id (id_internal)
                    if (isset($allDimRuller[$dimRul_item['id_internal']])) {
                        $dimRul = $allDimRuller[$dimRul_item['id_internal']];
//                        if (empty($dimRul->productsCount)) continue;
                        // Если такой есть
                        // Смотрим остаток
                        // Если уже закончился на сайте точьВточь
                        if (empty($dimRul->productsCount) || $dimRul->productsCount->remains == 0) {
                            // То смотрим, есть ли он на сайте firebox
                            if ($data_item['DimensionRulerAvailable'][$dimRul_id] == 'true') {
                                $avialable = true;
                                // Если есть, то добавляем в поставки еще 500 единиц товара.
                                $receipt = new Receipt;
                                $receipt->dimension_ruler_id = $dimRul->id;
                                $receipt->count = $data_item['Receipt'][$dimRul_id]['count'];
                                $receipt->provider_id = $data_item['Receipt'][$dimRul_id]['provider_id'];
                                $receipt->status = 1;
                                $receipt->save();
                                $update = true;
                                echo "Были добавлены единицы к размеру в товаре с id = " . $allProducts[$prod_id]->id . "\n";
                            }
                        } else {
                            // если на сайте точьВточь еще есть данный товар, смотрим есть ли он на сайте firebox
                            if ($data_item['DimensionRulerAvailable'][$dimRul_id] == 'false') {
                                // если уже нет, то находим все поступления данного размера, и вычитаем из них остаток, пока остаток не будет равен нулю
                                $count = $dimRul->productsCount->remains;
                                foreach ($dimRul->receipt as $receipt_id => $receipt_item) {
                                    if ($receipt_item->count > $count) {
                                        $receipt_item->count -= $count;
                                        $receipt_item->save();
                                        break;
                                    } else {
                                        $count -= $receipt_item->count;
                                        $receipt_item->delete();
                                        if ($count == 0) break;
                                    }
                                }
                                $update = true;
                                $count = null;
                                echo "Был удален размер в товаре с id = " . $allProducts[$prod_id]->id . "\n";
                            } else {
                                $avialable = true;
                            }
                        }
                        // Где-то id_internal остается преждний, а размер меняется. Так что нужно обновить
                        $dimRul->size = $dimRul_item['size'];
                        $dimRul->save();

                    } else {
                        if (!empty($data_item['Param'][$dimRul_id])) {
                            $transaction = \Yii::$app->db->beginTransaction();
                            try {
                                // Сохраняем размерную линейку
                                $dimensionruler = new DimensionRuler();
                                $dimRul_item['DimensionRuler'] = $dimRul_item;
                                $dimensionruler->load($dimRul_item);

                                // Ищем в базе, строку где точно такие же параметры одежды, какие указали при добавлении размеров
                                $dimensionruler = Param::searchEnteredParameters($dimRul_id, $allProducts[$prod_id]->model->category_id, $dimensionruler, $data_item['Param'][$dimRul_id]['value']);
                                $dimensionruler->product_id = $allProducts[$prod_id]->id;

                                // Если такой параметра уже существует в базе, то просто сохраняем или изменяем эту размерную линейку, с param = $dimensionruler->param_id
                                if ($dimensionruler->param_id) {
                                    if (!$flag = $dimensionruler->save()) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                } else { // Если такого параметра нет в базе
                                    // Сохраняем новые параметры в базу
                                    if (!$dimensionruler = Param::savingEnteredParameters($dimensionruler, $data_item['Param'][$dimRul_id]['value'], $allProducts[$prod_id]->model->category_id)) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                    // Сохраняем данную размерную линейку
                                    if (!$flag = $dimensionruler->save()) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                    // Ищем всех пользователей, для которых введеные параметры точьВточь
                                    $search_users = Param::SearchUsersWithEnteredParameters($data_item['Param'][$dimRul_id]['value'], $allProducts[$prod_id]->model->category_id);
                                    // Если такие пользователи найдены, то в таблицу paramid сохраняем данный параметр для данного пользователя
                                    if ($search_users) {
                                        $flag = Param::SavingEnteredParametersForUsers($search_users, $dimensionruler->param_id, $allProducts[$prod_id]->model->category_id);
                                        if (!$flag) {
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }
                                }
								
								if(is_object($dimensionruler)) {
									$receipt = new Receipt;
									$receipt->dimension_ruler_id = $dimensionruler->id;
									$receipt->count = $data_item['Receipt'][$dimRul_id]['count'];
									$receipt->provider_id = $data_item['Receipt'][$dimRul_id]['provider_id'];
									if (!($flag = $receipt->save(false))) {
										$transaction->rollBack();
										break;
									}
								}
                                if ($flag) {
                                    echo "Добавлен размер к товару с id = " . $allProducts[$prod_id]->id . "\n";
                                    $transaction->commit();
                                    $update = true;
                                }
                            } catch (\Exception $e) {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', "Не удалось удалить товар");
                            }
                        }
                    }
                }
              
                if($avialable == false) {
                    $allProducts[$prod_id]->status = 0;
                    $update = true;
                }
              
                if($update == true) {
                    $allProducts[$prod_id]->date_of_creation = date("Y-m-d");
                    $allProducts[$prod_id]->save();
                    $date_update++;
                }
            }
        }
        $shop_id= null;
        echo "Всего изменено дат - {$date_update}\n";
        echo "Всего замен - {$address}\n";
        echo "Всего цен изменено - {$price}\n";
    }
  
    /** размер обуви */
    public function size_obuv($size)
    {
        switch ($size) {
            case 38:
                $razmer_v_cm = 22;
                break;
            case 39:
                $razmer_v_cm = 17.5;
                break;
            case 1:
                $razmer_v_cm = 22.5;
                break;
            case 40:
                $razmer_v_cm = 23;
                break;
            case 41:
                $razmer_v_cm = 24.5;
                break;
            case 42:
                $razmer_v_cm = 27;
                break;
            case 43:
                $razmer_v_cm = 28.5;
                break;
            case 44:
                $razmer_v_cm = 29.5;
                break;
            case 45:
                $razmer_v_cm = 30.5;
                break;
            case 46:
                $razmer_v_cm = 32;
                break;
            case 47:
                $razmer_v_cm = 33;
                break;
            case 48:
                $razmer_v_cm = 34;
                break;
            case 2:
                $razmer_v_cm = 23.5;
                break;
            case 3:
                $razmer_v_cm = 24;
                break;
            case 4:
                $razmer_v_cm = 25;
                break;
            case 5:
                $razmer_v_cm = 25.5;
                break;
            case 6:
                $razmer_v_cm = 26;
                break;
            case 12:
                $razmer_v_cm = 26.5;
                break;
            case 13:
                $razmer_v_cm = 27.5;
                break;
            case 17:
                $razmer_v_cm = 28;
                break;
            case 18:
                $razmer_v_cm = 29;
                break;
            case 19:
                $razmer_v_cm = 30;
                break;
            case 20:
                $razmer_v_cm = 31;
                break;
            default:
                dd($size, 1);
        }
        return $razmer_v_cm;
    }
    /** размер Одежды 1 (Где мужик нарисован) */
    public function size_odejdi_2_man($size)
    {
        switch ($size) {
            case 7: //43
                $razmer_v_cm['OG'] = 86;
                $razmer_v_cm['OT'] = 74;
                $razmer_v_cm['OB'] = 94;
                break;
            case 8: //44
                $razmer_v_cm['OG'] = 90;
                $razmer_v_cm['OT'] = 78;
                $razmer_v_cm['OB'] = 97;
                break;
            case 9: //46
                $razmer_v_cm['OG'] = 94;
                $razmer_v_cm['OT'] = 82;
                $razmer_v_cm['OB'] = 98;
                break;
            case 10: //48
                $razmer_v_cm['OG'] = 98;
                $razmer_v_cm['OT'] = 86;
                $razmer_v_cm['OB'] = 103;
                break;
            case 11: //50
                $razmer_v_cm['OG'] = 102;
                $razmer_v_cm['OT'] = 90;
                $razmer_v_cm['OB'] = 106;
                break;
            case 14: //52
                $razmer_v_cm['OG'] = 106;
                $razmer_v_cm['OT'] = 94;
                $razmer_v_cm['OB'] = 109;
                break;
            case 15: //54
                $razmer_v_cm['OG'] = 110;
                $razmer_v_cm['OT'] = 100;
                $razmer_v_cm['OB'] = 112;
                break;
            case 16: //56
                $razmer_v_cm['OT'] = 114;
                $razmer_v_cm['OB'] = 104;
                $razmer_v_cm['OG'] = 115;
                break;
            default:
                dd($size, 1);
        }
        return $razmer_v_cm;
    }
    /** размер Одежды 1 (Где мужик нарисован) */
    public function size_odejdi_2_uni($size)
    {
        switch ($size) {
            case 7: //42
                $razmer_v_cm['OG'] = 86;
                $razmer_v_cm['OT'] = 66;
                $razmer_v_cm['OB'] = 92;
                break;
            case 8: //44
                $razmer_v_cm['OG'] = 90;
                $razmer_v_cm['OT'] = 78;
                $razmer_v_cm['OB'] = 97;
                break;
            case 9: //46
                $razmer_v_cm['OG'] = 94;
                $razmer_v_cm['OT'] = 82;
                $razmer_v_cm['OB'] = 98;
                break;
            case 10: //48
                $razmer_v_cm['OG'] = 98;
                $razmer_v_cm['OT'] = 86;
                $razmer_v_cm['OB'] = 103;
                break;
            case 11: //50
                $razmer_v_cm['OG'] = 102;
                $razmer_v_cm['OT'] = 90;
                $razmer_v_cm['OB'] = 106;
                break;
            case 14: //52
                $razmer_v_cm['OG'] = 106;
                $razmer_v_cm['OT'] = 94;
                $razmer_v_cm['OB'] = 109;
                break;
            case 15: //54
                $razmer_v_cm['OG'] = 110;
                $razmer_v_cm['OT'] = 100;
                $razmer_v_cm['OB'] = 112;
                break;
            case 16: //56
                $razmer_v_cm['OT'] = 114;
                $razmer_v_cm['OB'] = 104;
                $razmer_v_cm['OG'] = 115;
                break;
            default:
                dd($size, 1);
        }
        return $razmer_v_cm;
    }
    /** размер Одежды 1 (Где девушка нарисована) */
    public function size_odejdi_2_wom($size)
    {
        switch ($size) {
            case 7: //42
                $razmer_v_cm['OG'] = 86;
                $razmer_v_cm['OT'] = 66;
                $razmer_v_cm['OB'] = 92;
                break;
            case 8: //44
                $razmer_v_cm['OG'] = 90;
                $razmer_v_cm['OT'] = 70;
                $razmer_v_cm['OB'] = 96;
                break;
            case 9: //46
                $razmer_v_cm['OG'] = 94;
                $razmer_v_cm['OT'] = 74;
                $razmer_v_cm['OB'] = 100;
                break;
            case 10: //48
                $razmer_v_cm['OG'] = 98;
                $razmer_v_cm['OT'] = 78;
                $razmer_v_cm['OB'] = 104;
                break;
            case 11: //50
                $razmer_v_cm['OG'] = 102;
                $razmer_v_cm['OT'] = 82;
                $razmer_v_cm['OB'] = 108;
                break;
            case 14: //52
                $razmer_v_cm['OG'] = 106;
                $razmer_v_cm['OT'] = 86;
                $razmer_v_cm['OB'] = 112;
                break;
            default:
                dd($size, 1);
        }
        return $razmer_v_cm;
    }
    /** размер Одежды 1 без груди для мужчин (Где мужик нарисован) */
    public function size_odejdi_1_man($size)
    {
        switch ($size) {
            case 8: //44
                $razmer_v_cm['OT'] = 78;
                $razmer_v_cm['OB'] = 97;
                break;
            case 9: //46
                $razmer_v_cm['OT'] = 82;
                $razmer_v_cm['OB'] = 98;
                break;
            case 10: //48
                $razmer_v_cm['OT'] = 86;
                $razmer_v_cm['OB'] = 103;
                break;
            case 11: //50
                $razmer_v_cm['OT'] = 90;
                $razmer_v_cm['OB'] = 106;
                break;
            case 14: //52
                $razmer_v_cm['OT'] = 94;
                $razmer_v_cm['OB'] = 109;
                break;
            case 15: //54
                $razmer_v_cm['OT'] = 100;
                $razmer_v_cm['OB'] = 112;
                break;
            case 16: //56
                $razmer_v_cm['OT'] = 104;
                $razmer_v_cm['OB'] = 115;
                break;
            default:
                dd($size, 1);
        }
        return $razmer_v_cm;
    }
    /** размер Одежды 3 без груди */
    public function size_odejdi_3($size)
    {
        switch ($size) {
            case 7: //42
                $razmer_v_cm['OT'] = 72;
                $razmer_v_cm['OB'] = 91;
                $razmer_v_cm['DlinaBruk_15'] = 105;
                break;
            case 8: //44
                $razmer_v_cm['OT'] = 76;
                $razmer_v_cm['OB'] = 94;
                $razmer_v_cm['DlinaBruk_15'] = 105;
                break;
            case 9: //46
                $razmer_v_cm['OT'] = 80;
                $razmer_v_cm['OB'] = 97;
                $razmer_v_cm['DlinaBruk_15'] = 105;
                break;
            case 10: //48
                $razmer_v_cm['OT'] = 84;
                $razmer_v_cm['OB'] = 102;
                $razmer_v_cm['DlinaBruk_15'] = 109;
                break;
            case 11: //50
                $razmer_v_cm['OT'] = 88;
                $razmer_v_cm['OB'] = 106;
                $razmer_v_cm['DlinaBruk_15'] = 110;
                break;
            case 14: //52
                $razmer_v_cm['OT'] = 96;
                $razmer_v_cm['OB'] = 110;
                $razmer_v_cm['DlinaBruk_15'] = 110;
                break;
            case 15: //54
                $razmer_v_cm['OT'] = 100;
                $razmer_v_cm['OB'] = 112;
                $razmer_v_cm['DlinaBruk_15'] = 110;
                break;
            case 16: //56
                $razmer_v_cm['OT'] = 104;
                $razmer_v_cm['OB'] = 116;
                $razmer_v_cm['DlinaBruk_15'] = 110;
                break;
            default:
                dd($size, 1);
        }
        return $razmer_v_cm;
    }
}