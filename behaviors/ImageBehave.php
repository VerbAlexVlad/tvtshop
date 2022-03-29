<?php
namespace app\behaviors;

use app\base\Image;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use rico\yii2images\models;
use yii\helpers\BaseFileHelper;
use \rico\yii2images\ModuleTrait;

class ImageBehave extends \rico\yii2images\behaviors\ImageBehave
{
//    public function getImage()
//    {
//        if (empty($this->owner->mainImage)) {
//            return $this->getModule()->getPlaceHolder();
//        }
//        return $this->owner->mainImage;
//    }

   /**
   * function attachImage
   * $absolutePath - либо путь к файлу, либо base64
   */
    public function attachImage($absolutePath, $isMain = false, $name = '', $console=null, $type=null)
    {
        preg_match("/^data:image\/(.*);base64/i", substr($absolutePath, 0, 50), $match);

        if ($match) {
            $decoded = base64_decode(explode( ',', $absolutePath )[1]);
            $type = $match[1];
        } else {

            if(!preg_match('#http#', $absolutePath)){
                if (!file_exists($absolutePath)) {

                    throw new \Exception('File not exist! :'.$absolutePath);
                }
            }else{
                //nothing
            }

            if($type == null) {
                $type = pathinfo($absolutePath, PATHINFO_EXTENSION);
            }

        }
        if (!$this->owner->primaryKey) {
            throw new \Exception('Owner must have primaryKey when you attach image!');
        }

        if($type == 'png') {
            $image = imagecreatefrompng($absolutePath);

            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));


            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));

            imagealphablending($bg, TRUE);
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            imagedestroy($image);
            $quality = 50; // 0 = worst / smaller file, 100 = better / bigger file

            $pictureFileName = substr(md5(microtime(true) . $absolutePath), 4, 6);
            $pictureSubDir = $this->getModule()->getModelSubDir($this->owner);

            $storePath = $this->getModule()->getStorePath($this->owner);
            BaseFileHelper::createDirectory($storePath . DIRECTORY_SEPARATOR . $pictureSubDir,
                                            0775, true);

            $newAbsolutePath = $storePath .
                DIRECTORY_SEPARATOR . $pictureSubDir .
                DIRECTORY_SEPARATOR . $pictureFileName;

            if ($match) {
                file_put_contents($newAbsolutePath, $decoded);

                imagejpeg($bg, $newAbsolutePath . ".jpg", $quality);
                unlink($newAbsolutePath);

                imagedestroy($bg);
            } else {
                imagejpeg($bg, $newAbsolutePath . ".jpg", $quality);
                imagedestroy($bg);
            }

            if (!file_exists($newAbsolutePath . ".jpg")) {
                throw new \Exception('Cant copy file! ' . $absolutePath . ' to ' . $newAbsolutePath);
            }
            $pictureFileName .= '.jpg';
        } else {
            $pictureFileName =
                substr(md5(microtime(true) . $absolutePath), 4, 6)
                . '.' .
               $type;

            $pictureSubDir = $this->getModule()->getModelSubDir($this->owner);

            $storePath = $this->getModule()->getStorePath($this->owner);
            BaseFileHelper::createDirectory($storePath . DIRECTORY_SEPARATOR . $pictureSubDir,
                                            0775, true);

            $newAbsolutePath = $console . $storePath .
                DIRECTORY_SEPARATOR . $pictureSubDir .
                DIRECTORY_SEPARATOR . $pictureFileName;

            $dir = $console . $storePath . DIRECTORY_SEPARATOR . $pictureSubDir; // Например /var/www/localhost/public
            if(!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }

            if ($match) {
                file_put_contents($newAbsolutePath, $decoded);
            } else {
                echo "\n$absolutePath - {$newAbsolutePath}\n";
                copy($absolutePath, $newAbsolutePath);
            }

            if (!file_exists($newAbsolutePath)) {
                throw new \Exception('Cant copy file! ' . $absolutePath . ' to ' . $newAbsolutePath);
            }
        }

        if ($this->getModule()->className === null) {
            $image = new \app\base\Image;
        } else {
            $class = $this->getModule()->className;
            $image = new $class();
        }

        $image->itemId = $this->owner->primaryKey;
        $image->filePath = $pictureSubDir . '/' . $pictureFileName;
        $image->modelName = $this->getModule()->getShortClass($this->owner);
        $image->name = $name;

        $image->urlAlias = $this->getAlias($image);


        if(!$image->save()){
            return false;
        }

        if (count($image->getErrors()) > 0) {

            $ar = array_shift($image->getErrors());

            unlink($newAbsolutePath);
            throw new \Exception(array_shift($ar));
        }
        $img = $this->owner->getImage();

        //If main image not exists
        if(
            is_object($img) && get_class($img)=='rico\yii2images\models\PlaceHolder'
            or
            $img == null
            or
            $isMain
        ){
            $this->setMainImage($image);
        }

        return $image;
    }


    /** Make string part of image's url
     * @return string
     * @throws \Exception
     */
    private function getAliasString()
    {
        if ($this->createAliasMethod) {
            $string = $this->owner->{$this->createAliasMethod}();
            if (!is_string($string)) {
                throw new \Exception("Image's url must be string!");
            } else {
                return $string;
            }

        } else {
            return substr(md5(microtime()), 0, 10);
        }
    }

    /**
     *
     * Обновить алиасы для картинок
     * Зачистить кэш
     */
    private function getAlias()
    {
        $aliasWords = $this->getAliasString();
        $imagesCount = count($this->owner->getImages());

        return $aliasWords . '-' . intval($imagesCount + 1);
    }

    /**
     * Remove all model images
     */
    public function removeImages()
    {
        $images = $this->owner->getImages();
        if (count($images) > 1) {
            foreach ($images as $image) {
                if(!$flag = $this->owner->removeImage($image)) {
                    return false;
                }
            }
            $storePath = $this->getModule()->getStorePath($this->owner);
            $pictureSubDir = $this->getModule()->getModelSubDir($this->owner);
            $dirToRemove = $storePath . DIRECTORY_SEPARATOR . $pictureSubDir;
            BaseFileHelper::removeDirectory($dirToRemove);
        }
        return true;
    }
}