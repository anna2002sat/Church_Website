<?php

namespace frontend\controllers;

use frontend\models\Hierarchy;
use yii\web\Controller;

class HierarchyController extends Controller
{
    public function actionIndex()
    {
        $model=new Hierarchy();
        $categories = Hierarchy::find()->asArray()->where(['parent_id'=>0])->all() ;
//        $categories = $model->GetCatagoties();
        return $this->render('index', ['model'=> $model, 'categories'=>$categories]);
    }
}
