<?php
function isset_and_not_empty_get($get = null){
    if(isset($_GET[$get]) && !empty($_GET[$get])) {
        return true;
    }
    return false;
}
function isset_and_not_empty($q){
    if(isset($q) && !empty($q)) {
        return true;
    }
    return false;
}
function findCache($path, $get_name){
    $clone_cache = clone Yii::$app->cache;
    $clone_cache->cachePath = '/var/www/www-root/data/www/tvtshop.ru/runtime/cache/'.$path;

    /**
     * После смены пути, ищем кэш
     */
    $cache = $clone_cache->get($get_name);

    // Смотрим, есть ли данный товар в кэше, для добавления.
    if(empty($cache)) {
        return false;
    }

    return $cache;
}


function AddCache($path, $get_name, $query) {
    $clone_cache = clone Yii::$app->cache;
    $clone_cache->cachePath = '/var/www/www-root/data/www/tvtshop.ru/runtime/cache/'.$path;
    /**
     * После смены пути, сохраняем кэш
     */
    $clone_cache->set($get_name, $query);
}

function dd($arr, $die = null) {
    echo '<pre>' . print_r($arr, true) , '</pre>';
    !$die ? die : null;
}

function dbg($q, $die = null) {
    if(!is_array($q)) {
        echo $q->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql;
        !$die ? die : null;
    } else {
        echo "Входящие данные являются массивом";
        !$die ? die : null;
    }
}

function cl_print_r ($var, $label = '')
{
    $str = json_encode(print_r ($var, true));
    echo "<script>console.group('".$label."');console.log('".$str."');console.groupEnd();</script>";
}
function cl_var_dump ($var, $label = '')
{
    ob_start();
    var_dump($var);
    $result = json_encode(ob_get_clean());
    echo "<script>console.group('".$label."');console.log('".$result."');console.groupEnd();</script>";
}

function post ($var = null)
{
    if($var){
        return Yii::$app->request->post($var);

    } else {
        return Yii::$app->request->post();

    }
}
function get ($var = null)
{
    if($var){
        return Yii::$app->request->get($var);
    } else {
        return Yii::$app->request->get();
    }
}

function curl_get ($url, $header = 0, $referer = 'http://google.com')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, $header); // Не интересуют заголовки
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0');
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;

}

function translit($s) {
    $s = (string) $s; // преобразуем в строковое значение
    $s = strip_tags($s); // убираем HTML-теги
    $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
    $s = trim($s); // убираем пробелы в начале и конце строки
    $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
    $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
    $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
    $s = str_replace(" ", "_", $s); // заменяем пробелы знаком минус
    $s = str_replace("-", "_", $s); // заменяем пробелы знаком минус
    return $s; // возвращаем результат
}

function check_url($url)
{
    if(preg_match("@^http://@i",$url)) $url = preg_replace("@(http://)+@i",'http://',$url);
    else if (preg_match("@^https://@i",$url)) $url = preg_replace("@(https://)+@i",'https://',$url);
    else $url = 'http://'.$url;


    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        return false;
    }
    else return $url;
}

function open_url($url)
{
    $url_c=parse_url($url);

    if (!empty($url_c['host']) and checkdnsrr('www.'.$url_c['host']))
    {
        // Ответ сервера
        if ($otvet=@get_headers($url)){
            return substr($otvet[0], 9, 3);
        }
    }
    return false;
}

function startt()
{
    $start = microtime(true);
    return $start;
}

function endd($start, $end = false){
    $finish = microtime(true);
    $delta = $finish - $start;
    $result = $delta . ' сек.';

    if(!$end) {
        dd($result);
    } else {
        return $result;
    }
}

function generate_password($number)
{
    $arr = array('a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','o','p','r','s',
        't','u','v','x','y','z',
        'A','B','C','D','E','F',
        'G','H','I','J','K','L',
        'M','N','O','P','R','S',
        'T','U','V','X','Y','Z',
        '1','2','3','4','5','6',
        '7','8','9','0');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
        // Вычисляем случайный индекс массива
        $index = rand(0, count($arr) - 1);
        $pass .= $arr[$index];
    }
    return $pass;
}


// Параметры функции- путь, права на файлы, права на папки
function chmod_r($path, $filemode, $dirmode) {
    if (is_dir($path) ) {
        if (!chmod($path, $dirmode)) {
            $dirmode_str=decoct($dirmode);
            print "Failed applying filemode '$dirmode_str' on directory '$path'\n";
            print "  `-> the directory '$path' will be skipped from recursive chmod\n";
            return;
        }
        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if($file != '.' && $file != '..') {  // skip self and parent pointing directories
                $fullpath = $path.'/'.$file;
                chmod_R($fullpath, $filemode,$dirmode);
            }
        }
        closedir($dh);
    } else {
        if (is_link($path)) {
            print "link '$path' is skipped\n";
            return;
        }
        if (!chmod($path, $filemode)) {
            $filemode_str=decoct($filemode);
            print "Failed applying filemode '$filemode_str' on file '$path'\n";
            return;
        }
    }
}

// Функция определения является ли файл картинкой
function is_image($filename) {
    $is = @getimagesize($filename);
    if ( !$is ) {
        return false;
    } elseif ( !in_array($is[2], array(1,2,3)) ) {
        return false;
    } else {
        return true;
    }
}

/**
 * void object2file - функция записи объекта в файл
 *
 * @param mixed value - объект, массив и т.д.
 * @param string filename - имя файла куда будет произведена запись данных
 * @return void
 *
 */
function object2file($value, $filename)
{
    $str_value = serialize($value);

    $f = fopen($filename, 'w');
    fwrite($f, $str_value);
    fclose($f);
}


/**
 * mixed object_from_file - функция восстановления данных объекта из файла
 *
 * @param string filename - имя файла откуда будет производиться восстановление данных
 * @return mixed
 *
 */
function object_from_file($filename)
{
    $file = file_get_contents($filename);
    $value = unserialize($file);
    return $value;
}
