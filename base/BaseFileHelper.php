<?php

namespace app\base;

use yii\base\ErrorException;

class BaseFileHelper extends \yii\helpers\BaseFileHelper
{
    /**
     * Removes a directory (and all its content) recursively.
     *
     * @param string $dir the directory to be deleted recursively.
     * @param array $options options for directory remove. Valid options are:
     *
     * - traverseSymlinks: boolean, whether symlinks to the directories should be traversed too.
     *   Defaults to `false`, meaning the content of the symlinked directory would not be deleted.
     *   Only symlink would be removed in that default case.
     *
     * @throws ErrorException in case of failure
     */
    public static function removeDirectory($dir, $options = [], $delMainDirectory = true)
    {
        if (!is_dir($dir)) {
            return;
        }
        if (!empty($options['traverseSymlinks']) || !is_link($dir)) {
            if (!($handle = opendir($dir))) {
                return;
            }
            while (($file = readdir($handle)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    static::removeDirectory($path, $options);
                } else {
                    static::unlink($path);
                }
            }
            closedir($handle);
        }
        if (is_link($dir)) {
            static::unlink($dir);
        } else if($delMainDirectory){
            rmdir($dir);
        }
    }
  
}