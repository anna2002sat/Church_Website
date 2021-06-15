<?php


namespace common\rbac;


use frontend\models\Employee;
use frontend\models\Project;
use frontend\models\TaskEmployee;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;


class OwnProjectTaskRule extends Rule
{

    public $name = 'OwnsTask';

    public function execute($user, $item, $params)
    {
        $project = Project::findOne(['project_id'=>$params['task']->project_id]);
        $employee = Employee::findOne(['user_id'=>$user]);
        return isset($project) ? $project->author_id == $employee->employee_id : false;
    }
}