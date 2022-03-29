<?php
namespace app\modules\admin\widgets;

use Yii;
use yii\base\Arrayable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\i18n\Formatter;
use Closure;

class DetailView extends \yii\widgets\DetailView
{
    /**
     * @inheritdoc
     */
    protected function renderAttribute($attribute, $index)
    {
        if (is_string($this->template)) {
            $captionOptions = Html::renderTagAttributes(ArrayHelper::getValue($attribute, 'captionOptions', []));
            $contentOptions = Html::renderTagAttributes(ArrayHelper::getValue($attribute, 'contentOptions', []));
            if ($attribute['value'] instanceof Closure) {
                $value = call_user_func($attribute['value'], $model);
            } else {
                $value = $this->formatter->format($attribute['value'], $attribute['format']);
            }
            return strtr($this->template, [
                '{label}' => $attribute['label'],
                '{value}' => $value,
                '{captionOptions}' => $captionOptions,
                '{contentOptions}' => $contentOptions,
            ]);
        }
        return call_user_func($this->template, $attribute, $index, $this);
    }
}