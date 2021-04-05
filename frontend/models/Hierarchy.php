<?php


namespace frontend\models;

use yii\base\Model;
use yii\db\ActiveRecord;

class Hierarchy extends ActiveRecord
{
    public function getChildren($id){
        $children= Hierarchy::findAll(['parent_id' => $id]);
        return $children;
    }

    public function hasChildren($id){
        $child = Hierarchy::findOne(['parent_id' => $id]);
        if ($child!=null)
            return true;
        return false;
    }

    public function GetDepartments(){
        $departments= Department::find()->joinWith('department_type')->asArray()->select(['department_id','department_name', 'parent_id', 'name'] )->orderBy('parent_id')->all();
        for ($i=0; $i<count($departments); $i++){
            $departments[$i]['color'] = ($departments[$i]['name'] == 'Board') ? '#70ad47': (($departments[$i]['name'] == 'Center') ? '#2f5597' : '#5b9bd5') ;
        }
        return $departments;
    }
}