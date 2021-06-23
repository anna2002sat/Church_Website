<?php

namespace frontend\controllers;

use frontend\models\Doer;
use frontend\models\Employee;
use frontend\models\Project;
use frontend\models\Task;
use frontend\models\TaskEmployee;
use frontend\models\TaskEmployeeSearch;
use frontend\models\TaskSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class TaskController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','view', 'create', 'update', 'delete', 'doers', 'delete_doer', 'accept', 'apply'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['create', 'update', 'delete', 'delete_doer', 'index', 'accept'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'doers', 'apply'],
                        'roles' => ['Employee'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete',  'delete_doer', 'accept'],
                        'roles' => ['Manager'],
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException("Access Denied");
                },
            ],
        ];
    }
    public function actionIndex($isMyProjects, $project_id=0, $isMyTasks=false) {
        $searchModel = new TaskSearch();
        if($isMyTasks){

            $employee = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
            if($employee->verified!=true){
                throw new ForbiddenHttpException("Access Denied");
            }
            $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $employee->employee_id])->asArray()->all();

            $searchModel->task_ids = $my_tasks_ids;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        else{
            $searchModel->project_id=$project_id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $model = Project::findOne($project_id);
        }

        return $this->render('/task/index', compact('model', 'dataProvider', 'searchModel', 'isMyProjects', 'isMyTasks'));
    }

    public function actionView($id, $isMyProjects, $isMyTasks=false)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'isMyProjects'=>$isMyProjects,
            'isMyTasks'=>$isMyTasks
        ]);
    }

    public function actionUpdate($id, $isMyProjects, $isMyTasks=false)
    {
        $model = $this->findModel($id);

        if (!Yii::$app->user->can('updateTask', ['task' => $model])) {
            throw new ForbiddenHttpException("Access Denied");
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]);
        }

        return $this->render('update', [
            'model' => $model,
            'isMyProjects'=>$isMyProjects,
            'isMyTasks'=>$isMyTasks
        ]);
    }

    public function actionDoers($task_id, $isMyProjects, $isMyTasks=false)
    {
        $model = $this->findModel($task_id);

        $doers_ids = TaskEmployee::find()->select('employee_id')->where(['task_id' => $task_id, 'verified'=>true])->asArray()->all();

        $searchModel= new TaskEmployeeSearch();
        $searchModel->task_id=$task_id;
        $searchModel->verified=true;
        $doers = $searchModel->search(Yii::$app->request->queryParams);

        $free_employees = Employee::find()->where(['not in', 'employee_id', $doers_ids])->andWhere(['verified'=>true])->all();


        return $this->render('doers', [
            'model' => $model,
            'doers' => $doers,
            'free_employees' => $free_employees,
            'searchModel'=>$searchModel,
            'isMyProjects'=>$isMyProjects,
            'isMyTasks'=>$isMyTasks
        ]);
    }

    public function actionDelete_me($task, $isMyProjects=false, $isMyTasks=false)
    {
        $employee  = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        $cancel_doer = TaskEmployee::findOne(['task_id' => $task, 'employee_id' => $employee->employee_id]);
        $cancel_doer->delete();
        return $this->redirect(['doers', 'task_id' => $task, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]);
    }

    public function actionApply($task_id, $isMyProjects){
        $employee=Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        $task=Task::findOne(['task_id'=>$task_id]);
        if (!TaskEmployee::findOne(['task_id' => $task_id, 'employee_id' => $employee->employee_id])) {
            $new_doer = new TaskEmployee();
            $new_doer->task_id = $task_id;
            $new_doer->employee_id = $employee->employee_id;
            if(Yii::$app->user->can('updateProject', ['project'=>$task->project])){
                $new_doer->verified=true;
            }
            if($new_doer->save()){
                if(Yii::$app->user->can('updateProject', ['project'=>$task->project])){
                    Yii::$app->session->setFlash('success', 'You have been successfully added for the task!');
                }
                else {
                    Yii::$app->session->setFlash('success', 'You have successfully applied for the task!
                        Please wait for the manager to let you in!');
                }
                return $this->redirect(['index',
                    'project_id'=>Task::findOne(['task_id'=>$task_id])->project_id,
                    'isMyProjects'=>$isMyProjects
                ]);
            }
        }
        Yii::$app->session->setFlash('danger', 'Something went wrong! Try again later!');
        return $this->redirect(['index',
            'project_id'=>Task::findOne(['task_id'=>$task_id])->project_id,
            'isMyProjects'=>$isMyProjects
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
