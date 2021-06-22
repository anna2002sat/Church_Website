<?php

namespace backend\controllers;

use backend\models\Doer;
use backend\models\Employee;
use backend\models\Project;
use backend\models\Task;
use backend\models\TaskEmployee;
use backend\models\TaskEmployeeSearch;
use backend\models\TaskSearch;
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
                        'actions' => ['index','view', 'create', 'update', 'delete', 'doers', 'delete_doer', 'accept', 'apply'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'doers', 'apply', 'create', 'delete',  'delete_doer', 'accept'],
                        'roles' => ['Manager'],
                    ],
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
            $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $employee->employee_id, 'verified'=>true])->asArray()->all();

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
    public function actionCreate($isMyProjects, $project_id=null, $isMyTasks=false)
    {
        $model = new Task();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/task/view', 'id' => $model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]);
        }
        if($project_id){
            $project = Project::findOne(['project_id'=>$project_id]);
            $model->project_id = $project->project_id;
        }
        return $this->render('create', [
            'model' => $model,
            'isMyProjects' => $isMyProjects,
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

    public function actionDelete($id, $isMyProjects, $isMyTasks=false)
    {
        $model = $this->findModel($id);
        $project = Project::findOne(['project_id' => $model->project_id]);
        if (!Yii::$app->user->can('updateProject', ['project' => $project])) {
            throw new ForbiddenHttpException("Access Denied");
        }
        TaskEmployee::deleteAll(['task_id'=>$id]);
        $model->delete();

        return $this->redirect(['index', 'project_id'=>$project->project_id,'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]);
    }

    public function actionDoers($task_id, $isMyProjects, $isMyTasks=false)
    {
        $model = $this->findModel($task_id);
        $doer = new Doer();

        if (Yii::$app->request->isPjax) {
            $doer->load(Yii::$app->request->post());
            if ($doer->doer_id > 0) {
                if($doerEx=TaskEmployee::findOne(['task_id'=>$task_id, 'employee_id'=>$doer->doer_id])){
                    $doerEx->verified = true;
                    $doerEx->save();
                }
                else{
                    $new_doer = new TaskEmployee();
                    $new_doer->task_id = $task_id;
                    $new_doer->employee_id = $doer->doer_id;
                    $new_doer->verified = true;
                    $new_doer->save();
                }
                $doer->doer_id = 0;
            }
        }

        $doers_ids = TaskEmployee::find()->select('employee_id')->where(['task_id' => $task_id, 'verified'=>true])->asArray()->all();

        $searchModel= new TaskEmployeeSearch();
        $searchModel->task_id=$task_id;
        $searchModel->verified=true;
        $doers = $searchModel->search(Yii::$app->request->queryParams);

        $free_employees = Employee::find()->where(['not in', 'employee_id', $doers_ids])->andWhere(['verified'=>true])->all();


        return $this->render('doers', [
            'model' => $model,
            'doers' => $doers,
            'doer' => $doer,
            'free_employees' => $free_employees,
            'searchModel'=>$searchModel,
            'isMyProjects'=>$isMyProjects,
            'isMyTasks'=>$isMyTasks
        ]);
    }
    public function actionDelete_doer($doer, $task, $isMyProjects=false, $isMyTasks=false, $isDeny=false)
    {
        $cancel_doer = TaskEmployee::findOne(['task_id' => $task, 'employee_id' => $doer]);
        $cancel_doer->delete();
        if($isDeny){
            return $this->redirect(['/employee/messages']);
        }
        return $this->redirect(['doers', 'task_id' => $task, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]);
    }
    public function actionAccept($doer_id, $task_id){
        $employee = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        $my_project_ids= Project::find()->where(['author_id'=>$employee->employee_id])->select('project_id');
        $my_tasks = Task::find()->where(['project_id' => $my_project_ids])->select('task_id')->asArray()->all();
        $doer = TaskEmployee::find()->where(['in', 'task_id', $my_tasks])->andWhere(['employee_id'=>$doer_id])->all();
        if($doer){
            if ($new_doer = TaskEmployee::findOne(['task_id' => $task_id, 'employee_id' => $doer_id])) {
                $new_doer->verified = true;
                $new_doer->save();
                return $this->redirect(['/employee/messages']);
            }
        }
        throw new ForbiddenHttpException("Access Denied");
    }

    public function actionApply($task_id, $isMyProjects){
        $employee=Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        if (!TaskEmployee::findOne(['task_id' => $task_id, 'employee_id' => $employee->employee_id])) {
            $new_doer = new TaskEmployee();
            $new_doer->task_id = $task_id;

            $new_doer->employee_id = $employee->employee_id;
            if($new_doer->save()){
                Yii::$app->session->setFlash('success', 'You have successfully applied for the task!
             Please wait for the manager to let you in!');
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
