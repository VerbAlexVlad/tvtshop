<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\commands;
use app\modules\admin\models\Keywords;
use app\modules\admin\models\ProductModelContent;
use app\modules\admin\models\DimensionRuler;
use app\modules\admin\models\ProductModel;
use app\modules\admin\models\Categories;
use app\modules\admin\models\Products;
use app\modules\admin\models\ProductsKeys;
use app\modules\admin\models\Receipt;
use app\modules\admin\models\Param;
use app\modules\admin\models\Brand;
use app\modules\admin\models\Parameters;
use yii\console\Controller;
use app\base\Model;
use Yii;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FirefoxController extends Controller
{
    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionIndex()
    {
        $date_today = date("m.d.y"); //присвоено 12.03.15
        $today[1] = date("H:i:s", strtotime("+8 hours")); //присвоит 1 элементу массива 18:32:17
        echo("Интеграция началась в: {$today[1]}, {$date_today} .\n");

        ini_set('max_execution_time', 4000);
        ini_set('memory_limit', -1);

        require('/var/www/www-root/data/www/tvtshop.ru/functions.php');

        $data = [];


        $reader = new \XMLReader();
        $reader->open('http://fireboxclub.com/link/c719e206eeb504cad710f1e75e51e222a9f798b1.xml'); // указываем ридеру что будем парсить этот файл
        $id = 0;
      
      
        // циклическое чтение документа
        while ($reader->read()) {
            if ($reader->nodeType == \XMLReader::ELEMENT) {
                switch ($reader->localName) {
                    case 'offer':
                        if(isset($data[$id]['brand'])) {
                            if ($data[$id]['brand'] == 'Timberland' || $data[$id]['brand'] == 'VANS') {
                                unset($data[$id]);
                            }
                        }

                        $id = $reader->getAttribute('id');
                        $data[$id]['dimensionRuler_id'] = $reader->getAttribute('id');
                        $data[$id]['available'] = $reader->getAttribute('available');

                        break;
                    case 'name':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['name'] = trim($reader->value);
                            $mystring = trim($reader->value);
                            $findme = 'Куртк';
                            $pos = strpos($mystring, $findme);
                            if ($pos !== false) {
                                $rr[] = $reader->value;
                            }
                        }
                        break;
                    case 'url':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['url'] = str_ireplace("fireboxclub", "fireboxbiz", trim($reader->value));
                        }
                        break;
                    case 'price':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['price'] = trim($reader->value);
                        }
                        break;
                    case 'oldprice':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['oldprice'] = trim($reader->value);
                        }
                        break;
                    case 'picture':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['picture'][] = trim($reader->value);
                        }
                        break;
                    case 'vendor':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['brand'] = trim($reader->value);


                        }
                        break;
                    case 'vendorCode':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['product_id'] = trim($reader->value);
                        }
                        break;
                    case 'description':
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['description'] = trim($reader->value);
                        }
                        break;
                    case 'param':
                        $gg[trim($reader->getAttribute('name'))] = 1;
                        $data_name = trim($reader->getAttribute('name'));

                        // читаем дальше для получения текстового элемента
                        $reader->read();
                        if ($reader->nodeType == \XMLReader::TEXT) {
                            $data[$id]['param'][$data_name] = $reader->value;
                        }
                        break;
                }
            }
        }
        if ($data[0]) {
            unset($data[0]);
        }
        $result = [];
        foreach ($data as $i => $items) {
            if (isset($items['param']['ПодКатегория'])) {
                if (!isset($result[$items['param']['ПодКатегория']][$items['product_id']])) {
                    $result[$items['param']['ПодКатегория']][$items['product_id']] = $items;
                }
                if (isset($items['param']['Размер'])) {
                    switch ($items['param']['ПодКатегория']) {
                        case 'повседневная обувь':
                        case 'Кеды':
                        case 'средние':
                        case 'Скейтбординг':
                        case 'бутсы':
                        case 'Ботинки':
                        case 'асфальт/трейлраннинг':
                        case 'трек/зал':
                        case 'Сапоги':
                        case 'низкие':
                        case 'грунтовки':
                        case 'сандали/сланцы':
                        case 'Улица':
                        case 'высокие':
                        case 'бампы':
                            switch ($items['param']['Размер']) {
                                case '35':
                                    $size = 38;
                                    break;
                                case '36':
                                    $size = 1;
                                    break;
                                case '37':
                                    $size = 2;
                                    break;
                                case '38':
                                    $size = 3;
                                    break;
                                case '39':
                                    $size = 4;
                                    break;
                                case '40':
                                    $size = 5;
                                    break;
                                case '41':
                                    $size = 6;
                                    break;
                                case '42':
                                    $size = 12;
                                    break;
                                case '43':
                                    $size = 13;
                                    break;
                                case '44':
                                    $size = 17;
                                    break;
                                case '45':
                                    $size = 18;
                                    break;
                                case '46':
                                    $size = 19;
                                    break;
                                case '47':
                                    $size = 20;
                                    break;
                                case '28':
                                    $size = 39;
                                    break;
                                case '36.5':
                                    $size = 40;
                                    break;
                                case '38.5':
                                    $size = 41;
                                    break;
                                case '42.5':
                                    $size = 42;
                                    break;
                                case '44.5':
                                    $size = 43;
                                    break;
                                case '45.5':
                                    $size = 44;
                                    break;
                                case '46.5':
                                    $size = 45;
                                    break;
                                case '48':
                                    $size = 46;
                                    break;
                                case '49':
                                    $size = 47;
                                    break;
                                case '50':
                                    $size = 48;
                                    break;
                                default:
                                    echo 'Другой размер (1) '. $items['param']['Размер'];
                            }
                            break;
                        default:
                            switch ($items['param']['Размер']) {
                                case '42':
                                    $size = 7;
                                    break;
                                case '44':
                                    $size = 8;
                                    break;
                                case '46':
                                    $size = 9;
                                    break;
                                case '48':
                                    $size = 10;
                                    break;
                                case '50':
                                    $size = 11;
                                    break;
                                case '52':
                                    $size = 14;
                                    break;
                                case '54':
                                    $size = 15;
                                    break;
                                case '56':
                                    $size = 16;
                                    break;
                                case '58':
                                    $size = 29;
                                    break;
                                    break;
                                case '60':
                                    $size = 30;
                                    break;
                                case '62':
                                    $size = 31;
                                    break;
                                default:
                                    echo 'Другой размер (2) ' . $items['param']['Размер'];
                            }
                    }
                    $result[$items['param']['ПодКатегория']][$items['product_id']]['size'][] = $size;
                    $result[$items['param']['ПодКатегория']][$items['product_id']]['dimRul_id'][] = $items['dimensionRuler_id'];
                    $result[$items['param']['ПодКатегория']][$items['product_id']]['dim_rul_available'][] = $items['available'];
                }
            } elseif (isset($items['param']['Категория'])) {
                if (!isset($result[$items['param']['Категория']][$items['product_id']])) {
                    $result[$items['param']['Категория']][$items['product_id']] = $items;
                }

                if (isset($items['param']['Размер'])) {
                    switch ($items['param']['Категория']) {
                        case 'повседневная обувь':
                        case 'Кеды':
                        case 'средние':
                        case 'Скейтбординг':
                        case 'бутсы':
                        case 'Ботинки':
                        case 'асфальт/трейлраннинг':
                        case 'трек/зал':
                        case 'Сапоги':
                        case 'низкие':
                        case 'грунтовки':
                        case 'сандали/сланцы':
                        case 'Улица':
                        case 'высокие':
                        case 'бампы':
                            switch ($items['param']['Размер']) {
                                case '35':
                                    $size = 38;
                                    break;
                                case '36':
                                    $size = 1;
                                    break;
                                case '37':
                                    $size = 2;
                                    break;
                                case '38':
                                    $size = 3;
                                    break;
                                case '39':
                                    $size = 4;
                                    break;
                                case '40':
                                    $size = 5;
                                    break;
                                case '41':
                                    $size = 6;
                                    break;
                                case '42':
                                    $size = 12;
                                    break;
                                case '43':
                                    $size = 13;
                                    break;
                                case '44':
                                    $size = 17;
                                    break;
                                case '45':
                                    $size = 18;
                                    break;
                                case '46':
                                    $size = 19;
                                    break;
                                case '47':
                                    $size = 20;
                                    break;
                                case '28':
                                    $size = 39;
                                    break;
                                case '36.5':
                                    $size = 40;
                                    break;
                                case '38.5':
                                    $size = 41;
                                    break;
                                case '42.5':
                                    $size = 42;
                                    break;
                                case '44.5':
                                    $size = 43;
                                    break;
                                case '45.5':
                                    $size = 44;
                                    break;
                                case '46.5':
                                    $size = 45;
                                    break;
                                case '48':
                                    $size = 46;
                                    break;
                                case '49':
                                    $size = 47;
                                    break;
                                case '50':
                                    $size = 48;
                                    break;
                                default:
                                    echo 'Другой размер (3) '.$size;
                            }
                            break;
                        default:
                            switch ($items['param']['Размер']) {
                                case '42':
                                    $size = 7;
                                    break;
                                case '44':
                                    $size = 8;
                                    break;
                                case '46':
                                    $size = 9;
                                    break;
                                case '48':
                                    $size = 10;
                                    break;
                                case '50':
                                    $size = 11;
                                    break;
                                case '52':
                                    $size = 14;
                                    break;
                                case '54':
                                    $size = 15;
                                    break;
                                case '56':
                                    $size = 16;
                                    break;
                                case '58':
                                    $size = 29;
                                    break;
                                case '60':
                                    $size = 30;
                                    break;
                                case '62':
                                    $size = 31;
                                    break;
                                default:
                                    echo 'Другой размер (4) '.$items['param']['Размер'];
                            }
                    }
                    $result[$items['param']['Категория']][$items['product_id']]['size'][] = $size;
                    $result[$items['param']['Категория']][$items['product_id']]['dimRul_id'][] = $items['dimensionRuler_id'];
                    $result[$items['param']['Категория']][$items['product_id']]['dim_rul_available'][] = $items['available'];
                }
            }
        }
        unset($data);
        foreach ($result as $ii => $dimRullers) {
            switch ($ii) {
                case 'повседневная обувь':
                    $category_id = 114;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Джоггеры':
                    $category_id = 125;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_1_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_1_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }
                            $result[$ii][$i]['product_param'][] = [
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Брюки спортивные':
                    $category_id = 167;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if(isset($dimRuller['param']['Пол'])) {
                                if ($dimRuller['param']['Пол'] == "Мужской") {
                                    $razmer_v_cm = $this->size_odejdi_1_man($size);
                                } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                    $razmer_v_cm = $this->size_odejdi_1_wom($size);
                                } else {
                                    $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                    dd($floor, 1);
                                    dd($dimRuller, 1);
                                }
                            } else {
                                $dimRuller['param']['Пол'] =  "Мужской";
                                $razmer_v_cm = $this->size_odejdi_1_man($size);
                            }
                            $result[$ii][$i]['product_param'][] = [
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Кеды':
                    $category_id = 115;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Брюки чинос':
                    $category_id = 136;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_odejdi_3($size);

                            $result[$ii][$i]['product_param'][] = [
                                '6'  => $razmer_v_cm['OT'],
                                '8'  => $razmer_v_cm['OB'],
                                '15' => $razmer_v_cm['DlinaBruk_15'],
                            ];
                        }
                    }
                    break;
                case 'средние':
                    $category_id = 141;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Скейтбординг':
                    $category_id = 116;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'бутсы':
                    $category_id = 120;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Ботинки':
                    $category_id = 117;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'асфальт/трейлраннинг':
                    $category_id = 123;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'трек/зал':
                    $category_id = 124;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Сапоги':
                    $category_id = 118;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'низкие':
                    $category_id = 142;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Куртки зимние':
                    $category_id = 90;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }

                            $result[$ii][$i]['product_param'][] = [
                                '7' => $razmer_v_cm['OG'],
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Футболки':
                    $category_id = 94;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if (isset($dimRuller['param']['Пол']) && $dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif (isset($dimRuller['param']['Пол']) && $dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $razmer_v_cm = $this->size_odejdi_2_uni($size);
                                $result[$ii][$i]['param']['Пол'] = "Унисекс";
                                //                                    $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
//                                    dd ($floor, 1);
//                                    dd ($result[$ii][$i], 1);
                            }

                            $result[$ii][$i]['product_param'][] = [
                                '7' => $razmer_v_cm['OG'],
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'грунтовки':
                    $category_id = 121;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'сандали/сланцы':
                    $category_id = 119;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Свитшоты':
                    $category_id = 130;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }

                            $result[$ii][$i]['product_param'][] = [
                                '7' => $razmer_v_cm['OG'],
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Рубашки':
                    $category_id = 132;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }

                            $result[$ii][$i]['product_param'][] = [
                                '7' => $razmer_v_cm['OG'],
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Улица':
                    $category_id = 114;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Поло':
                    $category_id = 94;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }

                            $result[$ii][$i]['product_param'][] = [
                                '7' => $razmer_v_cm['OG'],
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Спортивные костюмы':
                    $category_id = 133;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if(isset($dimRuller['param']['Пол'])) {
                                if ($dimRuller['param']['Пол'] == "Мужской") {
                                    $razmer_v_cm = $this->size_odejdi_2_man($size);
                                } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                    $razmer_v_cm = $this->size_odejdi_2_wom($size);
                                } else {
                                    $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                    dd($floor, 1);
                                    dd($dimRuller, 1);
                                }
                            } else {
                              $razmer_v_cm = $this->size_odejdi_2_man($size);
                            }

                            $result[$ii][$i]['product_param'][] = [
                                '7' => $razmer_v_cm['OG'],
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Ветровки':
                    $category_id = 134;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if(isset($dimRuller['param']['Пол'])) {
                                if ($dimRuller['param']['Пол'] == "Мужской") {
                                    $razmer_v_cm = $this->size_odejdi_2_man($size);
                                } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                    $razmer_v_cm = $this->size_odejdi_2_wom($size);
                                } else {
                                    $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                    dd($floor, 1);
                                    dd($dimRuller, 1);
                                }
                            } else {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            }

                            $result[$ii][$i]['product_param'][] = [
                                '7' => $razmer_v_cm['OG'],
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'высокие':
                    $category_id = 143;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Джинсы':
                    $category_id = 135;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }
                            $result[$ii][$i]['product_param'][] = [
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'бампы':
                    $category_id = 122;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            $razmer_v_cm = $this->size_obuv($size);
                            $result[$ii][$i]['product_param'][] = ['19' => $razmer_v_cm];
                        }
                    }
                    break;
                case 'Брюки карго':
                    $category_id = 136;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }
                            $result[$ii][$i]['product_param'][] = [
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Футбол':
                    $category_id = 100;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }
                            $result[$ii][$i]['product_param'][] = [
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Олимпийки':
                    $category_id = 138;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }
                            $result[$ii][$i]['product_param'][] = [
                                '7' => $razmer_v_cm['OG'],
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                case 'Тренировка':
                    $category_id = 139;
                    $result[$ii]['category_id'] = $category_id;
                    /** Проставляем размеры ОГ ОТ ОБ */
                    foreach ($dimRullers as $i => $dimRuller) {
                        foreach ($dimRuller['size'] as $size) {
                            if ($dimRuller['param']['Пол'] == "Мужской") {
                                $razmer_v_cm = $this->size_odejdi_2_man($size);
                            } elseif ($dimRuller['param']['Пол'] == "Женский") {
                                $razmer_v_cm = $this->size_odejdi_2_wom($size);
                            } else {
                                $floor = "Неизвестный пол - {$dimRuller['param']['Пол']}";
                                dd($floor, 1);
                                dd($dimRuller, 1);
                            }
                            $result[$ii][$i]['product_param'][] = [
                                '6' => $razmer_v_cm['OT'],
                                '8' => $razmer_v_cm['OB'],
                            ];
                        }
                    }
                    break;
                /**
                 * Аксессуары
                 */

//                    case 'Рюкзаки':
//                        $category_id = 147;
//                        $result[$ii]['category_id'] = $category_id;
//                        break;
//                    case 'Сумки':
//                        $category_id = 144;
//                        $result[$ii]['category_id'] = $category_id;
//                        break;
//                    case 'Браслеты':
//                        $category_id = 148;
//                        $result[$ii]['category_id'] = $category_id;
//                        break;
                /**
                 * Аксессуары
                 */
                //                    case 'Шорты, бриджи':
//                        $category_id = 1;
//                        $result[$ii]['category_id'] = $category_id;
//                        break;
                //                    case 'Носки':
//                        $category_id = 146;
//                        $result[$ii]['category_id'] = $category_id;
//                        break;
                //                     case 'Толстовки, худи, лонгслив':
//                         $category_id = ;
//                         break;
//                     case 'Куртки, жилеты':
//                         $category_id = ;
//                         break;
//                     case 'Шорты, майки':
//                         $category_id = ;
//                         break;
//                     case 'Головные уборы, перчатки, шарфы':
//                         $category_id = ;
//                         break;
//                     case 'Брюки спортивные, леггинсы':
//                         $category_id = ;
//                         break;
//                     case 'Топы, майки':
//                         $category_id = ;
//                         break;
//                     case 'Ремни, кошельки':
//                         $category_id = ;
//                         break;
//                     case 'Спорт. Инвентарь':
//                         $category_id = ;
//                         break;
//                     case 'Джемпера, кардиганы':
//                         $category_id = ;
//                         break;
                default:
                    unset($result[$ii]);
            }
        }
        $query = [];
        foreach ($result as $iio => $items) {
            foreach ($items as $id => $item) {
                if ($id == 'category_id') continue;
                if(isset($item['param']['Пол'])) {
                    switch ($item['param']['Пол']) {
                        case 'Унисекс':
                            $floor = 0;
                            break;
                        case 'Мужской':
                            $floor = 1;
                            break;
                        case 'Женский':
                            $floor = 2;
                            break;
                        default:
                            $floor = 0;
                            break;
                    }
                }
                if(isset($item['param']['Сезон'])) {
                    switch ($item['param']['Сезон']) {
                        case 'Зима':
                            $season = 2;
                            break;
                        case 'Демисезон':
                            $season = 1;
                            break;
                        case 'Лето':
                            $season = 4;
                            break;
                        default:
                            $season = 3;
                            break;
                    }
                }
                foreach ($item['product_param'] as $id_param => $param) {
                    $dimRul[] = [
                        'size'        => $item['size'][$id_param],
                        'price'       => $item['price'] * 1.6,
                        'id_internal' => $item['dimRul_id'][$id_param],
                    ];
                    $dimRulAvailable[] = $item['dim_rul_available'][$id_param];

                    $receipt[] = [
                        'count'       => 500,
                        'provider_id' => 2,
                    ];
                    $params[] = [
                        'value' => $param
                    ];
                }

                $price = 0;
              
                switch (true) {
                    case ($item['price'] < 100):
                        $price = $item['price'] + 200;
                        break;
                    case ($item['price'] < 500):
                        $price = $item['price'] + 300;
                        break;
                    case ($item['price'] < 1000):
                        $price = $item['price'] + 600;
                        break;
                    case ($item['price'] < 1500):
                        $price = $item['price'] + 1000;
                        break;
//                     case ($item['price'] < 2000):
//                         $price = $item['price'] + 1000;
//                         break;
//                     case ($item['price'] < 2500):
//                         $price = $item['price'] + 1200;
//                         break;
//                     case ($item['price'] < 3000):
//                         $price = $item['price'] + 1300;
//                         break;
//                     case ($item['price'] < 3500):
//                         $price = $item['price'] + 1400;
//                         break;
                    default:
                        $price = $item['price'] + 1200;
                        break;
                }
              

              
                $query[$id] = [
                    'ProductModel'        => [
                        'category_id' => $result[$iio]['category_id'],
                        'brand'       => $item['brand'],
                        'model'       => $item['name']
                    ],
                    'ProductModelContent' => [
                        'content' => [isset($item['description']) ? $item['description'] : null],
                    ],
                    'Products'            => [
                        'gallery'         => [$item['picture']],
                        'shops_id'        => 45,
                        'products_url'    => [$item['url']],
                        'floor'           => isset($floor) ? $floor : 0,
                        'season'          => isset($season) ? $season : 1,
                        'price'           => $price,
                        'price_wholesale' => ceil($item['price'] * 0.1)*10,
                        'color'           => isset($color) ? $color : null,
                        'id_internal'     => [$item['product_id']]
                    ],
                    'DimensionRuler'               => $dimRul,
                    'DimensionRulerAvailable'      => $dimRulAvailable,
                    'Receipt'                      => $receipt,
                    'Param'                        => $params
                ];
                unset($floor);
                unset($season);
                unset($dimRul);
                unset($receipt);
                unset($params);
                unset($dimRulAvailable);
                unset($color);
            }
        }
        // Находим все продукты
        $allProducts = Products::find()
            ->where(['shops_id' => 45])
            ->with([
                       'dimRuller'=>function($dimRuller){
                           $dimRuller->with(['remains', 'receipt']);
                       }
                   ])->all();
        foreach ($allProducts as $item) {
            // если в yml нет товара с id_internaml
            if(!isset($query[$item->id_internal])) {
                // если у товара есть размеры, проходим по ним, если нет, удаляем товар
                if(!empty($item->dimRuller)) {
                    $item->status = 0;
                    $item->save();
                    foreach ($item->dimRuller as $size) {
                        if(!empty($size->remains)) {
                            // заносим в переменную остаток по данному размеру
                            $remains_count = $size->remains->remains;
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
                    }
                } else {
                    $item->delete();
                }
            }
        }
        $this->integratsiya_s_saytom($query, 45);
        unset($allProducts);
        unset($query);

        // Проходит по всем товарам, и смотрит есть ли у него размеры, если нет, status=0
        $p = Products::find()
            ->where(['shops_id' => 45])
            ->with(
                [
                    'dimRuller' => function ($dimRuller) {
                        $dimRuller->with(['dimRulRemains']);
                    }
                ]
            )->all();
        $i=0;
        $ii=0;
        foreach ($p as $product) {
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
                $i++;
                $product->status = 0;
                $product->save();
            } elseif (($count > 0) && $product->status == 0) {
                $ii++;
                $product->status = 1;
                $product->save();
            }
        }
        dd('Статус 0 поставлен - ' . $i . ' товарам', 1);
        dd('Статус 1 поставлен - ' . $ii . ' товарам', 1);

        $date_today = date("m.d.y"); //присвоено 12.03.15
        $today[1] = date("H:i:s", strtotime("+8 hours")); //присвоит 1 элементу массива 18:32:17
        echo("Интеграция закончилась в: {$today[1]}, {$date_today} .\n");
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
            ->joinWith([
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
        $date_update = 0;
        $address = 0;
        $price = 0;

        foreach ($data as $prod_id=>$data_item) {
            if(empty($data_item['Products']['products_url'])) continue;
            if(!isset($allProducts[$prod_id])) {
                $pr = Products::find()->select('id')->where(['id_internal' => $prod_id])->andWhere(['shops_id' => $shop_id])->one();
                if($pr) dd("Херь!");
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
                        echo "Не удалось сохранить модель: \n";
                        continue;
                    };
                }
                try {
                    $transaction = \Yii::$app->db->beginTransaction();
                    $category = Categories::findOne($product_model->category_id);
                    foreach ($data_item['Products']['products_url'] as $id=>$item) {

                        if(!empty($data_item['ProductModelContent']['content'][$id])) {
                            $product_model_content = ProductModelContent::createContent($product_model->id, $data_item['ProductModelContent']['content'][$id]);
                            if (!($flag = $product_model_content->save())) {
                                $transaction->rollBack();
                                echo "Ошибка в описании \n";
                                continue;
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
                            if (!($flag = $products->uploadGallery('/var/www/www-root/data/www/tvtshop.ru/web/'))) {
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
                                    if(($data_item['DimensionRulerAvailable'][$dimensionruler_id] == 'true') && !empty($data_item['Param'][$dimensionruler_id])) {
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
                    echo "Какая-то ошибка блин \n";
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
        dd("Всего изменено дат - {$date_update}", 1);
        dd("Всего замен - {$address}", 1);
        dd("Всего цен изменено - {$price}", 1);
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

    /** размер Одежды 1 без груди для мужчин (Где мужик нарисован) */
    public function size_odejdi_1_wom($size)
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