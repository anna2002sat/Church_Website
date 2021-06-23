<?php

namespace frontend\controllers;


use frontend\models\AuthAssignment;
use frontend\models\EmployeeSearch;
use frontend\models\EmployeeUploadForm;
use frontend\models\Project;
use frontend\models\Task;
use frontend\models\TaskEmployee;
use frontend\models\User;
use Yii;
use frontend\models\Employee;
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
                'only' => ['index', 'view', 'create', 'update', 'my-profile', 'update-image', 'delete'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index','view', 'create', 'update', 'my-profile', 'update-image', 'delete'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'my-profile'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'my-profile', 'update-image', 'delete'],
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
        if ((Yii::$app->user->can('Employee'))){
            throw new ForbiddenHttpException("Access Denied");
        }
        $model = new Employee();

        if ($model->load(Yii::$app->request->post())){
            $model->email = User::findOne([Yii::$app->user->getId()])->email;
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




    public function actionMyProfile()
    {
        $model = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
        if (!$model) {
            return $this->redirect('create');
        }
        if($model->verified){
            $my_tasks_ids = TaskEmployee::find()->select('task_id')->where(['employee_id' => $model->employee_id,  'verified'=>true])->asArray()->all();
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

    public function actionDelete()
    {
        if(Yii::$app->user->can('Admin')){
            throw new ForbiddenHttpException("You are not allowed to delete admin profile!");
        }
        $employee = Employee::findOne(['user_id'=>Yii::$app->user->getId()]);

        TaskEmployee::deleteAll(['employee_id'=>$employee->employee_id]);
        AuthAssignment::deleteAll(['user_id'=>$employee->user_id]);
        $this->findModel($employee->employee_id)->delete();

        return $this->redirect(['my-profile']);
    }
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
