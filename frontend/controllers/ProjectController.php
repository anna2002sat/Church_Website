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
                'only' => ['index','view', 'create', 'update', 'delete', 'update-image'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['?'],
                    ],
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
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete', 'update', 'update-image'],
                        'roles' => ['Manager'],
                    ]

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
//        $comments = Comment::find()->all();
        return $this->render('view', compact('model', 'notEmpty', 'genderChart', 'statusChart', 'completionChart', 'overDueChart', 'isMyProjects', 'comments'));
    }




    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isMyProjects)
    {
        $model = new Project();
        if ($model->load(Yii::$app->request->post())){
            $upload = new ProjectUploadForm();
            $upload->image = UploadedFile::getInstance($model, 'image');
            if($model->save()) {
                $upload->saveImage($model->project_id);
                return $this->redirect(['view', 'id' => $model->project_id, 'isMyProjects'=>$isMyProjects]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'managers' => $this->allManagers(),
            'isMyProjects'=>$isMyProjects,
        ]);
    }

    public function actionUpdateImage($id, $isMyProjects){
        $project = $this->findModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$project])){
            throw new ForbiddenHttpException("Access Denied");
        }
        $model = new ProjectUploadForm();
        if (Yii::$app->request->isPost){
            if ($model->saveImage($id)){
                return $this->redirect(['view', 'id' =>$project->project_id, 'isMyProjects'=>$isMyProjects]);
            }
        }
        return $this->render('upload', [
            'model'=>$model,
            'project'=>$project,
            'isMyProjects'=>$isMyProjects,
            ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $isMyProjects)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$model])){
            throw new ForbiddenHttpException("Access Denied");
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()){
                return $this->redirect(['view', 'id' => $model->project_id, 'isMyProjects'=>$isMyProjects]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'managers' => $this->allManagers(),
            'isMyProjects'=>$isMyProjects
        ]);
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
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $isMyProjects)
    {
        $model= $this->findModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$model])){
            throw new ForbiddenHttpException("Access Denied");
        }
        $tasks=Task::find()->where(['project_id'=>$id])->select('task_id')->asArray();
        TaskEmployee::deleteAll(['task_id'=>$tasks]);
        Task::deleteAll(['project_id'=>$id]);
        $model->delete();
        return $this->redirect(['index', 'isMyProjects'=>$isMyProjects]);
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

    public function authorStatistics(){  /////// GROUP BY
        $query = Project::find();
        $query->joinWith(['author']);
        return $query->select(['COUNT(*) as count', 'author_id'])->groupBy('author_id')->asArray()->all();
    }

}
