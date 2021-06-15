<?php

namespace frontend\controllers;


use frontend\models\AuthAssignment;
use frontend\models\Doer;
use frontend\models\EmployeeSearch;
use frontend\models\EmployeeUploadForm;
use frontend\models\Project;
use frontend\models\ProjectSearch;
use frontend\models\ProjectUploadForm;
use frontend\models\Task;
use frontend\models\TaskEmployee;
use frontend\models\TaskEmployeeSearch;
use frontend\models\TaskSearch;
use frontend\models\User;
use Yii;
use frontend\models\Employee;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
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
                'only' => ['index', 'view', 'create', 'update', 'delete', 'my-profile', 'update-image',
                        'projects', 'project-view', 'project-create', 'project-update-image','project-update', 'project-delete',
                            'task-index', 'task-view', 'task-update', 'doers', 'task-create', 'task-delete', 'delete_doer',
                            'project-task-view', 'project-task-create', 'project-task-update', 'project_doers', 'project-tasks'
                    ],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index','view', 'create', 'update', 'delete', 'my-profile', 'update-image',
                            'projects', 'project-view', 'project-create', 'project-update-image','project-update', 'project-delete',
                            'task-index', 'task-view', 'task-update', 'doers', 'task-create', 'task-delete', 'delete_doer',
                            'project-task-view', 'project-task-create', 'project-task-update', 'project_doers', 'project-tasks'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'my-profile'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'my-profile', 'update-image',
                            'projects', 'project-view', 'task-index', 'task-view', 'task-update', 'doers',
                            'project-task-view', 'project-task-update', 'project_doers', 'project-tasks','delete'
                        ],
                        'roles' => ['Employee'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['project-create', 'project-update-image','project-update', 'project-delete',
                            'task-create', 'task-delete', 'delete_doer',
                             'project-task-create', 'project-task-update'],
                        'roles' => ['Manager'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException("Access Denied");
                },
            ],
        ];
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $employees = $searchModel->search(Yii::$app->request->queryParams);

        $employees = $employees->getModels();
        return $this->render('index', [
            'employees'=>$employees,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $model->employee_id])->asArray()->all();
        $my_tasks = Task::find()->where(['task_id'=>$my_tasks_ids])->select('task_id')->asArray()->all();

        return $this->render('view',
            [
                'model' => $model,
                'genderChart'=>$model->getGenderChart($my_tasks),
                'statusChart'=>$model->getStatusChart($my_tasks),
                'completionChart'=>$model->getCompletionChart($my_tasks),
                'overDueChart'=>$model->getOverDueChart($my_tasks),
                'empty'=>count($my_tasks)>0
            ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ((Yii::$app->user->can('Employee')) && !(Yii::$app->user->can('Admin'))){
            throw new ForbiddenHttpException("Access Denied");
        }
        $model = new Employee();
        $model->email = User::findOne([Yii::$app->user->getId()])->email;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->employee_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->can('employeeUpdate', ['employee'=>$model])){
            throw new ForbiddenHttpException("Access Denied");
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->employee_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->can('employeeUpdate', ['employee'=>$model])){
            throw new ForbiddenHttpException("Access Denied");
        }
        $current_user = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);

        TaskEmployee::deleteAll(['employee_id'=>$id]);
        $user_id = Employee::findOne(['employee_id'=>$id])->user_id;
        AuthAssignment::deleteAll(['user_id'=>$user_id]);
        $this->findModel($id)->delete();

        if($current_user->employee_id==$id)
            return $this->redirect(['my-profile']);
        return $this->redirect(['index']);
    }


    public function actionMyProfile()
    {
        $model = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        if (!$model) {
            return $this->redirect('create');
        }
        $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $model->employee_id])->asArray()->all();
        $my_tasks = Task::find()->where(['task_id'=>$my_tasks_ids])->select('task_id')->asArray()->all();

        return $this->render('view',
            [
                'model' => $model,
                'genderChart'=>$model->getGenderChart($my_tasks),
                'statusChart'=>$model->getStatusChart($my_tasks),
                'completionChart'=>$model->getCompletionChart($my_tasks),
                'overDueChart'=>$model->getOverDueChart($my_tasks),
                'empty'=>count($my_tasks)>0
            ]);
    }

    public function actionUpdateImage($id){
        $employee = $this->findModel($id);
        if (!Yii::$app->user->can('employeeUpdate', ['employee'=>$employee])){
            throw new ForbiddenHttpException("Access Denied");
        }
        $model = new EmployeeUploadForm();
        if (Yii::$app->request->isPost){
            if ($model->saveImage($id)){
                return $this->redirect(['employee/view', 'id'=>$id]);
            }
        }
        return $this->render('upload', ['model'=>$model, 'employee'=>$employee]);
    }


    // for all projects
    public function actionProjects()
    {
        $searchModel = new ProjectSearch();
        $employee_id = Employee::findOne(['user_id'=>Yii::$app->user->getId()])->employee_id;

        $my_projects = Project::find()->select('project_id')->where(['author_id'=>$employee_id])->asArray()->all();
        $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $employee_id])->asArray()->all();
        $my_taskProject_ids = Task::find()->where(['in','task_id', $my_tasks_ids])->select('project_id')->asArray()->all();
        $my_project_ids = Project::find()->where(['in','project_id', $my_taskProject_ids])->orWhere(['in','project_id', $my_projects])->select('project_id')->distinct()->asArray()->all();

        $searchModel->project_ids=$my_project_ids;
        $projects = $searchModel->search(Yii::$app->request->queryParams);


        $projects = $projects->getModels();

        return $this->render('project/index', [
            'searchModel' => $searchModel,
            'projects' => $projects,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionProjectView($id = 1)
    {
        $model= $this->findProjectModel($id);
        $genderChart = $model->getGenderChart();
        $statusChart = $model->getStatusChart();
        $completionChart = $model->getCompletionChart();
        $overDueChart = $model->getOverDueChart();
        $empty = Task::find()->where(['project_id'=>$id])->select('task_id')->count();
        return $this->render('project/view', compact('model', 'empty', 'genderChart', 'statusChart', 'completionChart', 'overDueChart'));
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProjectCreate()
    {
        $model = new Project();
        if ($model->load(Yii::$app->request->post())){
            $upload = new ProjectUploadForm();
            $upload->image = UploadedFile::getInstance($model, 'image');
            if($model->save()) {
                $upload->saveImage($model->project_id);
                return $this->redirect(['project-view', 'id' => $model->project_id]);
            }
        }
        return $this->render('project/create', [
            'model' => $model,
            'managers' => $this->allManagers()
        ]);
    }

    public function actionProjectUpdateImage($id){
        $project = $this->findProjectModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$project])){
            throw new ForbiddenHttpException("Access Denied");
        }
        $model = new ProjectUploadForm();
        if (Yii::$app->request->isPost){
            if ($model->saveImage($id)){
                return $this->redirect(['project-view', 'id' =>$project->project_id]);
            }
        }
        return $this->render('project/upload', [
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
    public function actionProjectUpdate($id)
    {
        $model = $this->findProjectModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$model])){
            throw new ForbiddenHttpException("Access Denied");
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save($id)){
                return $this->redirect(['project-view', 'id' => $model->project_id]);
            }
        }
        return $this->render('project/update', [
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
    public function actionProjectDelete($id)
    {
        $model= $this->findProjectModel($id);
        if (!Yii::$app->user->can('updateProject', ['project'=>$model])){
            throw new ForbiddenHttpException("Access Denied");
        }
        $tasks=Task::find()->where(['project_id'=>$id])->select('task_id')->asArray();
        TaskEmployee::deleteAll(['task_id'=>$tasks]);
        Task::deleteAll(['project_id'=>$id]);
        $model->delete();
        return $this->redirect(['projects']);
    }



    //// for all tasks
    public function actionTaskIndex()
    {
        $searchModel = new TaskSearch();

        $employee = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $employee->employee_id])->asArray()->all();

        $searchModel->task_ids = $my_tasks_ids;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('task/index',
            [
                'dataProvider'=>$dataProvider,
                'searchModel'=>$searchModel,
            ]);
    }

    public function actionTaskView($id)
    {
        return $this->render('task/view', [
            'model' => $this->findTaskModel($id),
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

    public function actionTaskCreate()
    {
        $model = new Task();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['task-view', 'id' => $model->task_id]);
        }
        return $this->render('task/create', [
            'model' => $model,
        ]);
    }

    public function actionTaskDelete($id)
    {
        $model = $this->findTaskModel($id);
        $project = $this->findProjectModel($model->project_id);
        if (!Yii::$app->user->can('updateProject', ['project' => $project])) {
            throw new ForbiddenHttpException("Access Denied");
        }
        TaskEmployee::deleteAll(['task_id'=>$id]);
        $model->delete();

        return $this->redirect(['/project/task-index', 'id'=>$project->project_id]);
    }

    public function actionDelete_doer($doer, $task)
    {
        $project = Project::findOne(['project_id'=>(Task::findOne(['task_id'=>$task])->project_id)]);
        if (!Yii::$app->user->can('updateProject', ['project' => $project])) {
            throw new ForbiddenHttpException("Access Denied");
        }
        $cancel_doer = TaskEmployee::findOne(['task_id' => $task, 'employee_id' => $doer]);
        $cancel_doer->delete();
        return $this->redirect(['doers', 'task_id' => $task]);
    }


    /// for task in project
    public function actionProjectTaskView($id)
    {

        return $this->render('project/task/view', [
            'model' => $this->findTaskModel($id),
        ]);
    }

    public function actionProjectTaskCreate($project_id)
    {
        $project = Project::findOne(['project_id'=>$project_id]);
        if (!Yii::$app->user->can('updateProject', ['project' => $project])) {
            throw new ForbiddenHttpException("Access Denied");
        }
        $model = new Task();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['task-view', 'id' => $model->task_id]);
        }
        $project = Project::findOne(['project_id' => $project_id]);
        $model->project_id = $project->project_id;
        return $this->render('project/task/create', [
            'model' => $model,
        ]);
    }

    public function actionProjectTaskUpdate($id)
    {
        $model = $this->findTaskModel($id);
        if (!Yii::$app->user->can('updateTask', ['task' => $model])) {
            throw new ForbiddenHttpException("Access Denied");
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['project-task-view', 'id' => $model->task_id]);
        }

        return $this->render('project/task/update', [
            'model' => $model,
        ]);
    }

    public function actionProjectDoers($task_id)
    {
        $model = $this->findTaskModel($task_id);
        $doer = new Doer();

        if (Yii::$app->request->isPjax) {
            $project = Project::findOne(['project_id' => $model->project_id]);

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

        return $this->render('project/task/doers', [
            'model' => $model,
            'doers' => $doers,
            'doer' => $doer,
            'free_employees' => $free_employees,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionProjectTasks($project_id = 1){
        $searchModel = new TaskSearch();
        $searchModel->project_id=$project_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $this->findProjectModel($project_id);

        return $this->render('project/task/index', compact('model', 'dataProvider', 'searchModel'));
    }


    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
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
    protected function findProjectModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionManagers(){
        if (!Yii::$app->user->can('Admin')){
            throw new ForbiddenHttpException("Access Denied");
        }
        $manager = new Doer();

        if (Yii::$app->request->isPjax) {
            $manager->load(Yii::$app->request->post());
            if ($manager->doer_id > 0) {
                $auth = Yii::$app->authManager;
                $managerRole= $auth->getRole('Manager');
                $employeeRole= $auth->getRole('Employee');
                $auth->revoke($employeeRole, $manager->doer_id);
                $auth->assign($managerRole, $manager->doer_id);
                $manager->doer_id = 0;
            }
        }
        $searchModel = new EmployeeSearch();

        $searchModel->full_role='Manager';
        $managers = $searchModel->search(Yii::$app->request->queryParams);

//        $managers = new ActiveDataProvider([
//            'query' => Employee::find()->where(['in', 'user_id', $managerRoles]),
//            'pagination' => [
//                'pageSize' => 20,
//            ],
//        ]);
        $managerRoles = AuthAssignment::find()->where(['item_name'=>'Manager'])->select('user_id')->asArray()->all();
        $free_employees = Employee::find()->where(['not in', 'user_id', $managerRoles])->andWhere(['!=','user_id','1'])->all();

        return $this->render('managers', [
            'managers' => $managers,
            'manager' => $manager,
            'free_employees' => $free_employees,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionDeleteManager($user_id)
    {
        $auth = Yii::$app->authManager;
        $role= $auth->getRole('Manager');
        $employeeRole= $auth->getRole('Employee');
        $auth->assign($employeeRole, $user_id);
        $auth->revoke($role, $user_id);

        return $this->redirect(['managers']);
    }


}
