<?php


namespace common\rbac;


use frontend\models\Employee;
use frontend\models\TaskEmployee;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;


class OwnTaskRule extends Rule
{

    public $name = 'isInTask';

    public function execute($user, $item, $params)
    {
        $employee = Employee::findOne(['user_id'=>$user]);
        $isInTask = TaskEmployee::findOne(['employee_id'=>$employee->employee_id, 'task_id'=>$params['task']->task_id]);
        return isset($isInTask);
    }
}