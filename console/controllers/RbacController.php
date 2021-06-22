<?php


namespace console\controllers;


use Yii;
use yii\console\Controller;
use yii\db\Migration;
use yii\db\Schema;

class RbacController extends Migration
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // adding permission to UpdateAny
        $employeeUpdate= $auth->createPermission('employeeUpdate');
        $employeeUpdate->description='Update Employee';
        $auth->add($employeeUpdate);

        // adding permission to Delete
        $employeeDelete= $auth->createPermission('employeeDelete');
        $employeeDelete->description='Delete Employee';
        $auth->add($employeeDelete);

        // adding permission to Update Own Profile
        $rule = new \common\rbac\OwnProfileRule;
        $auth->add($rule);
        $employeeUpdateOwnProfile= $auth->createPermission('employeeUpdateOwnProfile');
        $employeeUpdateOwnProfile->description='Update Own Profile';
        $employeeUpdateOwnProfile->ruleName = $rule->name;
        $auth->add($employeeUpdateOwnProfile);


        // creating needed roles
        $employee = $auth->createRole('Employee');
        $manager = $auth->createRole('Manager');
        $admin = $auth->createRole('Admin');

        $auth->add($employee);
        $auth->add($manager);
        $auth->add($admin);


        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $employeeDelete);
        $auth->addChild($admin, $employeeUpdate);
        $auth->addChild($manager, $employee);
        $auth->addChild($employee, $employeeUpdateOwnProfile);
        $auth->addChild($employeeUpdateOwnProfile, $employeeUpdate);


        $auth->assign($admin,1);
        $auth->assign($manager,2);
        $auth->assign($employee,3);
    }

    public function actionOwnProjectRule()
    {
        $auth = Yii::$app->authManager;

        $manager= $auth->getRole('Manager');
        $admin= $auth->getRole('Admin');

        $UpdateProject= $auth->createPermission('updateProject');
        $UpdateProject->description='Update Project';
        $auth->add($UpdateProject);


        $rule = new \common\rbac\OwnProjectRule();
        $auth->add($rule);
        $updateOwnProject= $auth->createPermission('updateOwnProject');
        $updateOwnProject->description='Update Own Project';
        $updateOwnProject->ruleName = $rule->name;


        $auth->add($updateOwnProject);

        $auth->addChild($admin, $UpdateProject);
        $auth->addChild($manager, $updateOwnProject);
        $auth->addChild($updateOwnProject, $UpdateProject);
    }
    function actionEditstatus()
    {
        $auth = Yii::$app->authManager;
        $employee = $auth->getRole('Employee');
        $manager = $auth->getRole('Manager');
        $admin = $auth->getRole('Admin');

        $rule = new \common\rbac\OwnTaskRule();
        $auth->add($rule);

        $updateTaskStatus= $auth->createPermission('updateTaskStatus');
        $updateTaskStatus->description='Update Own Task Status';
        $updateTaskStatus->ruleName = $rule->name;
        $auth->add($updateTaskStatus);

        $rule = new \common\rbac\OwnProjectTaskRule();
        $auth->add($rule);

        $updateOwnTask= $auth->createPermission('updateOwnTask');
        $updateOwnTask->description='Update Own Task';
        $updateOwnTask->ruleName = $rule->name;
        $auth->add($updateOwnTask);

        $updateTask= $auth->createPermission('updateTask');
        $updateTask->description='Update Task';
        $auth->add($updateTask);



        $auth->addChild($admin, $updateTask);
        $auth->addChild($manager, $updateOwnTask);
        $auth->addChild($updateOwnTask, $updateTask);
        $auth->addChild($employee, $updateTaskStatus);
        $auth->addChild($updateTaskStatus, $updateTask);

    }
}