<?php


namespace frontend\controllers;


use frontend\models\Salary;
use yii\web\Controller;

class SalaryController extends Controller
{
    public function actionIndex(){
        $model = new Salary();
        return $this->render('index', ['model' => $model]);
    }
    public static function actionCount(){
            if(isset($_POST['values']))
            {
                $salary= new Salary();
                $result=$salary->CountSal($_POST['values']);
                echo $result.'*';
            }
            else
                echo 'failure';
    }

}