<?php

namespace backend\controllers;


use backend\models\AuthAssignment;
use backend\models\Doer;
use backend\models\EmployeeSearch;
use backend\models\EmployeeUploadForm;
use backend\models\Project;
use backend\models\Task;
use backend\models\TaskEmployee;
use backend\models\User;
use Yii;
use backend\models\Employee;
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
                'only' => ['index', 'view', 'create', 'update', 'delete', 'my-profile', 'update-image'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'my-profile', 'update-image'],
                        'roles' => ['?'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'my-profile', 'update-image'],
                        'roles' => ['Manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'my-profile', 'update-image'],
                        'roles' => ['Admin'],
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
        $searchModel->verified=1;
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
        if($model->verified) {
            $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $model->employee_id])->asArray()->all();
            $my_tasks = Task::find()->where(['task_id' => $my_tasks_ids])->select('task_id')->asArray()->all();

            return $this->render('view',
                [
                    'model' => $model,
                    'genderChart' => $model->getGenderChart($my_tasks),
                    'statusChart' => $model->getStatusChart($my_tasks),
                    'completionChart' => $model->getCompletionChart($my_tasks),
                    'overDueChart' => $model->getOverDueChart($my_tasks),
                    'empty' => count($my_tasks) > 0
                ]);
        }
        else
            throw new ForbiddenHttpException("Access Denied");
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

        if ($model->load(Yii::$app->request->post())){
            if (!Yii::$app->user->can('Admin')){
                $model->email = User::findOne([Yii::$app->user->getId()])->email;
            }
            else{
                $model->verified=true;
            }
            if (!$model->about)
                $model->about = 'Nothing much ;)';
            $upload = new EmployeeUploadForm();
            $upload->imageFile = UploadedFile::getInstance($model, 'image');
            if ($model->save()) {
                $upload->saveImage($model->employee_id);
                if(!$model->verified) {
                    Yii::$app->session->setFlash('success', 'Your employee profile is successfully created! 
                            You will receive the email after it is verified by administrator! Thanks for understanding!');
                    return $this->redirect(['/site']);
                }
                else
                    return $this->redirect(['view', 'id' => $model->employee_id]);
            }
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
        $oldEmail=$model->email;
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->about)
                $model->about = 'Nothing much ;)';
            if ($model->save()) {
                if($oldEmail!=$model->email && !Yii::$app->user->can('Admin'))
                    return $this->redirect(['site/index']);
                return $this->redirect(['view', 'id' => $model->employee_id]);
            }
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
    public function actionDelete($id, $deny=false)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->can('employeeUpdate', ['employee'=>$model])){
            throw new ForbiddenHttpException("You are not allowed to delete this profile!");
        }
        if($model->user_id==Yii::$app->user->getId() && Yii::$app->user->can('Admin')){
            throw new ForbiddenHttpException("You are not allowed to delete admin profile!");
        }
        $current_user = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);

        TaskEmployee::deleteAll(['employee_id'=>$id]);
        $user_id = Employee::findOne(['employee_id'=>$id])->user_id;
        AuthAssignment::deleteAll(['user_id'=>$user_id]);
        $this->findModel($id)->delete();

        if($deny) {
            return $this->redirect(['messages']);
        }
        if($current_user->employee_id==$id)
            return $this->redirect(['_-profile']);
        return $this->redirect(['index']);
    }
    public function actionAccept($user_id){
        if($user_id){
            $auth = Yii::$app->authManager;
            $employeeRole= $auth->getRole('Employee');
            $auth->assign($employeeRole,$user_id);
            $employee= Employee::findOne(['user_id'=>$user_id]);
            $employee->verified=true;
            $employee->save();
        }
        else
            Yii::$app->session->setFlash('danger', 'This employee doesn`t have user account yet');
        return $this->redirect(['messages']);
    }


    public function actionMyProfile()
    {
        $model = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        if (!$model) {
            return $this->redirect('create');
        }
        if($model->verified){
            $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $model->employee_id, 'verified'=>true])->asArray()->all();
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
        Yii::$app->session->setFlash('danger', 'Your employee profile has not been confirmed by administrator yet!
         For more information contact the administrator by filling the form bellow!
         Thanks for understanding!');
        return $this->redirect(['site/contact']);
    }
    public function actionMessages(){
        if(Yii::$app->user->can('Admin')){
            $new_employees= Employee::find()->where(['verified'=>'0'])->all();
        }
        if(Yii::$app->user->can('Manager')){
            $employee = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
            $my_project_ids= Project::find()->where(['author_id'=>$employee->employee_id])->select('project_id');
            $my_tasks = Task::find()->where(['project_id' => $my_project_ids])->select('task_id')->asArray()->all();
            $new_doers = TaskEmployee::find()->where(['in', 'task_id', $my_tasks])
                ->andWhere(['verified'=>false])->asArray()->all();

            for($i=0; $i<count($new_doers); $i++){
                $employee = Employee::findOne(['employee_id' => $new_doers[$i]['employee_id']]);
                $new_doers[$i]['employee_img']= $employee->getImage();
                $new_doers[$i]['employee_id']= $employee->employee_id;
                $task = Task::findOne(['task_id'=>$new_doers[$i]['task_id']]);
                $project =  Project::findOne(['project_id'=>[$task->project_id]]);
                $new_doers[$i]['project_img']= $project->getImage();
                $new_doers[$i]['project_id']= $project->project_id;
                $new_doers[$i]['project_name']= $project->title;
                $new_doers[$i]['task_name'] = $task->title;
                $new_doers[$i]['doer'] = Employee::findOne(['employee_id'=>$new_doers[$i]['employee_id']])->getFullname();
            }
        }
        else{
            throw new ForbiddenHttpException("Access Denied");
        }

        return $this->render('messages',
        [
            'new_employees'=>$new_employees,
                'new_doers'=>$new_doers,
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
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
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
        $employee = Employee::findOne(['user_id'=>$user_id]);
        $curr_employee = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        $authorships = Project::find()->where(['author_id'=>$employee->employee_id])->all();
        foreach ($authorships as $authorship) {
            $authorship->author_id = $curr_employee->employee_id;
            $authorship->save();
        };

        $auth = Yii::$app->authManager;
        $role= $auth->getRole('Manager');
        $employeeRole= $auth->getRole('Employee');
        $auth->assign($employeeRole, $user_id);
        $auth->revoke($role, $user_id);

        return $this->redirect(['managers']);
    }
}
