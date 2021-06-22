<?php


namespace backend\models;
use yii\base\Model;
use yii\web\UploadedFile;

class EmployeeUploadForm extends Model
{
    public $imageFile;
    private $oldImageName;
    private $newImageName;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty'=>false, 'extensions'=>'png, jpg'],
        ];
    }

    public function setNewImageName(){
        $this->newImageName = \Yii::$app->security->generateRandomString(). '.'. $this->imageFile->extension;
    }

    public function upload(){
        if($this->validate()){
            $this->imageFile->saveAs($this->getFolder() . $this->newImageName);
            return true;
        }
        else
            return false;
    }

    public function getFolder(){
        return \Yii::getAlias('@frontend'.'/web/images/employee/');
    }

    public function saveImage($id){
        if(!$this->imageFile)
            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        if(!$this->validate()){
            return false;
        }
        $employee = Employee::findOne($id);
        $this->setNewImageName();
        if($this->upload()){
            $this->oldImageName = $employee->image;
            $employee->image= $this->newImageName;
            if ($employee->save()){
                FileSystem::deleteFile($this->getFolder() . $this->oldImageName);
                return true;
            }
            FileSystem::deleteFile($this->getFolder() . $this->newImageName);
            return false;
        }
        return false;
    }
}