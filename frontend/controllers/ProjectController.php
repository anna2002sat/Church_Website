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
                'only' => ['index','view', 'create', 'update', 'delete','tasks',
                    'task-view', 'task-create', 'task-update', 'task-delete', 'doers', 'delete_doer', 'update-image'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['create', 'update', 'task-create', 'task-update', 'task-delete', 'delete_doer', 'tasks'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','view'],
                        'roles' => ['?'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'task-view', 'task-update', 'doers', 'tasks'],
                        'roles' => ['Employee'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete', 'update', 'task-delete', 'delete_doer', 'update-image',  'task-create'],
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
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();

        $projects = $searchModel->search(Yii::$app->request->queryParams);

        $projects = $projects->getModels();
        $authorsStat = $this->authorStatistics();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'projects' => $projects,
            'authorsStat' => $authorsStat
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id = 1)
    {
        $model= $this->findModel($id);
        $genderChart = $model->getGenderChart();
        $statusChart = $model->getStatusChart();
        $completionChart = $model->getCompletionChart();
        $overDueChart = $model->getOverDueChart();
        $empty = Task::find()->where(['project_id'=>$id])->select('task_id')->count();
        return $this->render('view', compact('model', 'empty', 'genderChart', 'statusChart', 'completionChart', 'overDueChart'));
    }


    public function actionTasks($project_id = 1){
        $searchModel = new TaskSearch();
        $searchModel->project_id=$project_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findModel($project_id);
        return $this->render('task/index', compact('model', 'dataProvider', 'searchModel'));
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        if ($model->load(Yii::$app->request->post())){
            $upload = new ProjectUploadForm();
            $upload->image = UploadedFile::getInstance($model, 'image');
            if($model->save()) {
                $upload->saveImage($model->project_id);
                return $this->redirect(['view', 'id' => $model->project_id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'managers' => $this->allManagers()
        ]);
    }

    public function actionUpdateImage($id){
        $project = $this->findModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$project])){
            throw new ForbiddenHttpException("Access Denied");
        }
        $model = new ProjectUploadForm();
        if (Yii::$app->request->isPost){
            if ($model->saveImage($id)){
                return $this->redirect(['view', 'id' =>$project->project_id]);
            }
        }
        return $this->render('upload', [
            'model'=>$model,
            'project'=>$project,
            ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$model])){
            throw new ForbiddenHttpException("Access Denied");
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save($id)){
                return $this->redirect(['view', 'id' => $model->project_id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'managers' => $this->allManagers()
        ]);
    }
    private function allManagers(){
        $managersIds = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Manager'])->orWhere(['item_name' => 'Admin'])->asArray()->all();
        $managers = Employee::find()->where(['in', 'user_id', $managersIds])->asArray()->all();
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
    public function actionDelete($id)
    {
        $model= $this->findModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$model])){
            throw new ForbiddenHttpException("Access Denied");
        }
        $tasks=Task::find()->where(['project_id'=>$id])->select('task_id')->asArray();
        TaskEmployee::deleteAll(['task_id'=>$tasks]);
        Task::deleteAll(['project_id'=>$id]);
        $model->delete();
        return $this->redirect(['index']);
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
    protected function findTaskModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionTaskView($id)
    {
        return $this->render('task/view', [
            'model' => $this->findTaskModel($id),
        ]);
    }
    public function actionTaskCreate($project_id)
    {
        $model = new Task();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['task-view', 'id' => $model->task_id]);
        }
        $project = Project::findOne(['project_id'=>$project_id]);
        $model->project_id = $project->project_id;
        return $this->render('task/create', [
            'model' => $model,
        ]);
    }

    public function actionTaskUpdate($id)
    {
        $model = $this->findTaskModel($id);

        if (!Yii::$app->user->can('updateTask', ['task' => $model])) {
            throw new ForbiddenHttpException("Access Denied");
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['task-view', 'id' => $model->task_id]);
        }

        return $this->render('task/update', [
            'model' => $model,
        ]);
    }

    public function actionTaskDelete($id)
    {
        $model = $this->findTaskModel($id);
        $project = Project::findOne(['project_id' => $model->project_id]);
        if (!Yii::$app->user->can('updateProject', ['project' => $project])) {
            throw new ForbiddenHttpException("Access Denied");
        }
        TaskEmployee::deleteAll(['task_id'=>$id]);
        $model->delete();

        return $this->redirect(['tasks', 'project_id'=>$project->project_id]);
    }

    public function actionDoers($task_id)
    {
        $model = $this->findTaskModel($task_id);
        $doer = new Doer();

        if (Yii::$app->request->isPjax) {
            $doer->load(Yii::$app->request->post());
            if ($doer->doer_id > 0) {
                $new_doer = new TaskEmployee();
                $new_doer->task_id = $task_id;
                $new_doer->employee_id = $doer->doer_id;
                $new_doer->save();
                $doer->doer_id = 0;
            }
        }

        $doers_ids = TaskEmployee::find()->select('employee_id')->where(['task_id' => $task_id])->asArray()->all();

        $searchModel= new TaskEmployeeSearch();
        $searchModel->task_id=$task_id;
        $doers = $searchModel->search(Yii::$app->request->queryParams);

        $free_employees = Employee::find()->where(['not in', 'employee_id', $doers_ids])->all();


        return $this->render('task/doers', [
            'model' => $model,
            'doers' => $doers,
            'doer' => $doer,
            'free_employees' => $free_employees,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionDelete_doer($doer, $task)
    {
        $cancel_doer = TaskEmployee::findOne(['task_id' => $task, 'employee_id' => $doer]);
        $cancel_doer->delete();
        return $this->redirect(['doers', 'task_id' => $task]);
    }

    public function authorStatistics(){  /////// GROUP BY
        $query = Project::find();
        $query->joinWith(['author']);
        return $query->select(['COUNT(*) as count', 'author_id'])->groupBy('author_id')->asArray()->all();
    }

}
