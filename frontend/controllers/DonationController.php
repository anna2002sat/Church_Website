<?php

namespace frontend\controllers;

use frontend\models\Employee;
use frontend\models\Project;
use frontend\models\User;
use Yii;
use frontend\models\Donation;
use frontend\models\DonationSearch;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DonationController implements the CRUD actions for Donation model.
 */
class DonationController extends Controller
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
                'only' => ['index', 'create'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','create'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException("Access Denied");
                },
            ],
        ];

    }

    /**
     * Lists all Donation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DonationSearch();
        if(Yii::$app->user->isGuest)
        {
            throw new ForbiddenHttpException("Access Denied");
        }
        $user=User::findOne(['id'=>Yii::$app->user->getId()]);


        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(!$dataProvider->getCount()){
            Yii::$app->session->setFlash('info', 'You haven`t made any donations yet! Press Donate Button to make one!');
        }
        else
            Yii::$app->session->setFlash('success', 'Thank you for supporting us!');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'my'=>$my
        ]);
    }

    /**
     * Creates a new Donation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Donation();
        if(Yii::$app->user->can('Employee')){
            $employee= Employee::findOne(['user_id'=>Yii::$app->user->getId()]);
            $model->last_name=$employee->last_name;
            $model->first_name=$employee->first_name;
        }
        if ($model->load(Yii::$app->request->post())){
            if(!Yii::$app->user->isGuest){
                $user=User::findOne(['id'=>Yii::$app->user->getId()]);
                $model->email=$user->email;
            }
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Thank you for your donation!');
                if($model->project_id)
                {
                    $project = Project::findOne(['project_id'=>$model->project_id]);
                    $project->collected_sum += $model->amount;
                    $project->save();
                }
                return $this->redirect(['create', 'id' => $model->donation_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Donation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Donation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Donation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
