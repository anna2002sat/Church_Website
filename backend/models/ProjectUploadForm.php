<?php


namespace backend\models;
use yii\base\Model;
use yii\web\UploadedFile;

class ProjectUploadForm extends Model
{
    public $image;
    private $oldImageName;
    private $newImageName;


    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty'=>false, 'extensions'=>'png, jpg'],
        ];
    }

    public function setNewImageName(){
        $this->newImageName = \Yii::$app->security->generateRandomString(). '.'. $this->image->extension;
    }

    public function upload(){
        if($this->validate()){
            $this->image->saveAs($this->getFolder() . $this->newImageName);
            return true;
        }
        else
            return false;
    }

    public function getFolder(){
        return \Yii::getAlias('@frontend'.'/web/images/projects/');
    }

    public function saveImage($id){
        if(!$this->image)
            $this->image = UploadedFile::getInstance($this, 'image');
        if(!$this->validate()){
            return false;
        }
        $project = Project::findOne($id);
        $this->setNewImageName();
        if($this->upload()){
            $this->oldImageName = $project->image;
            $project->image= $this->newImageName;
            if ($project->save()){
                FileSystem::deleteFile($this->getFolder() . $this->oldImageName);
                return true;
            }
            FileSystem::deleteFile($this->getFolder() . $this->newImageName);
            return false;
        }
        return false;
    }

}