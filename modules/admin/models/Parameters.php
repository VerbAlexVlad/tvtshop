<?php

namespace app\modules\admin\models;

class Parameters extends \app\models\Parameters
{
        /**
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) {
            $path = 'images/store/' . $this->image->baseName . '.' . $this->image->extension;
            $this->image->saveAs($path);
            $this->attachImage($path, true);
            @unlink($path);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Удаление всех картинок для данной позиции
     * @return bool
     */
    public function deleteImages()
    {
        $this->removeImages();

        return true;
    }
}
