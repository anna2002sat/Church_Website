<?php

namespace frontend\controllers;


use frontend\models\AuthAssignment;
use frontend\models\Doer;
use frontend\models\Employee;
use frontend\models\EmployeeSearch;
use frontend\models\ProjectUploadForm;
use frontend\models\Task;
use frontend\models\TaskEmployee;
use frontend\models\TaskEmployeeSearch;
use frontend\models\TaskSearch;
use Yii;
use frontend\models\Project;
use frontend\models\ProjectSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    /**
     * {@inheritdoc}
     */

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
                'only' => ['index','view'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','view'],
                        'roles' => ['Employee'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException("Access Denied");
                },
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @param bool $employee_id
     * @return mixed
     */
    public function actionIndex($isMyProjects=false)
    {
        $searchModel = new ProjectSearch();

        if($isMyProjects){
            if(Yii::$app->user->isGuest || !Yii::$app->user->can('Employee')){
                throw new ForbiddenHttpException("Access Denied");
            }
            $employee = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);

            if($employee->verified!=true){
                throw new ForbiddenHttpException("Access Denied");
            }
            $employee_id = $employee->employee_id;
            $my_projects = Project::find()->select('project_id')->where(['author_id'=>$employee_id])->asArray()->all();
            $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $employee_id])->asArray()->all();
            $my_taskProject_ids = Task::find()->where(['in','task_id', $my_tasks_ids])->select('project_id')->asArray()->all();
            $my_project_ids = Project::find()->where(['in','project_id', $my_taskProject_ids])->orWhere(['in','project_id', $my_projects])->select('project_id')->distinct()->asArray()->all();

            $searchModel->project_ids=$my_project_ids;
            $isMyProjects = true;
        }
        $projects = $searchModel->search(Yii::$app->request->queryParams);
        $projects = $projects->getModels();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'projects' => $projects,
            'isMyProjects' => $isMyProjects
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($isMyProjects=false, $id = 1)
    {
        $model= $this->findModel($id);
        $genderChart = $model->getGenderChart();
        $statusChart = $model->getStatusChart();
        $completionChart = $model->getCompletionChart();
        $overDueChart = $model->getOverDueChart();
        $notEmpty = Task::find()->where(['project_id'=>$id])->select('task_id')->count();
        return $this->render('view', compact('model', 'notEmpty', 'genderChart', 'statusChart', 'completionChart', 'overDueChart', 'isMyProjects', 'comments'));
    }


    private function allManagers(){
        $managersIds = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Manager'])->orWhere(['item_name' => 'Admin'])->asArray()->all();
        $managers = Employee::find()->where(['in', 'user_id', $managersIds])->where(['verified'=>true])->asArray()->all();
        for ($i = 0; $i < count($managers); $i++) {
            $managers[$i]['fullname'] = $managers[$i]['first_name'] . ' ' . $managers[$i]['last_name'];
        }
        return $managers;
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
