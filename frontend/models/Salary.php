<?php


namespace frontend\models;


use yii\db\ActiveRecord;

class Salary extends ActiveRecord
{
    public $salary = [];

    public function rules() {
        return [
            ['salary', 'each', 'rule' => ['float'], 'message' => 'Це поле має бути числом']
        ];
    }

    public function CountSal($values){
        $result=0;
        foreach ($values as $value){
            $result+=$value;
        }
        return $result;
    }
}