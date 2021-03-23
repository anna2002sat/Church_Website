<?php

namespace frontend\controllers;

use frontend\models\HierarchyModel;
use yii\web\Controller;

class HierarchyController extends Controller
{
    public function actionIndex()
    {
        $model=new HierarchyModel();
        $categories = Hierarchy::find()->asArray()->where(['parent_id'=>0])->all() ;
//        $categories = $model->GetCatagoties();
        return $this->render('index', ['model'=> $model, 'categories'=>$categories]);
    }
}
