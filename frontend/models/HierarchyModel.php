<?php


namespace frontend\models;


use yii\base\Model;
use \frontend\controllers\Hierarchy;

class HierarchyModel extends Model
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


}