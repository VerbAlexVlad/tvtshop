<?php
namespace app\base;

use CdekSDK2\Actions\Action;
use CdekSDK2\Actions\FilteredTrait;

class Calculator extends Action
{
    use FilteredTrait;

    /**
     * URL для запросов к API
     * @var string
     */
    public const URL = '/calculator/tariff';

    /**
     * Список корректных параметров, которые разрешено передавать для поиска офисов
     * @var array
     */
    public const FILTER = [
        'date' => '',
        'type' => '',
        'currency' => '',
        'tariff_code' => '',
        'from_location' => '',
        'to_location' => '',
        'services' => '',
        'packages' => '',
    ];
}