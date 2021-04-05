<?php

namespace frontend\controllers;

use frontend\models\Department;
use frontend\models\Department_type;
use frontend\models\Hierarchy;
use yii\web\Controller;

class HierarchyController extends Controller
{
    public function actionIndex()
    {
        $model = new Hierarchy();
        $categories = Hierarchy::find()->asArray()->where(['parent_id'=>0])->all() ;
        $departments= $model->GetDepartments();

        return $this->render('index', ['list_model'=> $model,
            'categories'=>$categories,  'departments' =>$departments]);
    }
}
