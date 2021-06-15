<?php


namespace common\rbac;


use frontend\models\Employee;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;


class OwnProjectRule extends Rule
{

    public $name = 'ownsProject';

    public function execute($user, $item, $params)
    {
        $employee = Employee::findOne(['user_id'=>$user]);
        return isset($params['project']) ? $params['project']->author_id == $employee->employee_id : false;
    }
}