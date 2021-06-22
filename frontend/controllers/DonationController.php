<?php

namespace frontend\controllers;

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
                'only' => ['index', 'create', 'delete'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index', 'delete'],
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
                    [
                        'allow' => true,
                        'actions' => ['create', 'index', 'delete',],
                        'roles' => ['Admin'],
                    ]
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
    public function actionIndex($my=false)
    {
        $searchModel = new DonationSearch();
        if(Yii::$app->user->isGuest)
        {
            throw new ForbiddenHttpException("Access Denied");
        }
        if($my)
        {
            $user=User::findOne(['id'=>Yii::$app->user->getId()]);
            $searchModel->email=$user->email;
        }
        else{
            if(!Yii::$app->user->can('Admin'))
            {
                throw new ForbiddenHttpException("Access Denied");
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if($my){
            if(!$dataProvider->getCount()){
                Yii::$app->session->setFlash('info', 'You haven`t made any donations yet! Press Donate Button to make one!');
            }
            else
                Yii::$app->session->setFlash('success', 'Thank you for supporting us!');
        }

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
     * Deletes an existing Donation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
