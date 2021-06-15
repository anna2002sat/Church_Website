<?php
namespace frontend\models;

use phpDocumentor\Reflection\File;
use yii\base\Model;

class FileSystem extends Model
{
    public static function deleteFile($filepath)
    {
        if (is_file($filepath))
        {
            unlink($filepath);
        }
    }
}
