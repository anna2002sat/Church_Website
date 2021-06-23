<?php

use frontend\models\Employee;
use rmrevin\yii\fontawesome\FAS;
use kartik\rating\StarRating;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\web\ForbiddenHttpException;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1 class="container text-center" style="font-family: 'Algerian'"><?= Html::encode($this->title) ?></h1>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    --><?//= GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
////        'header'=>'Actions',
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
////            'employee_id',
//            [
//                'attribute' => 'full_name',
//                'value' => 'fullname',
//            ],
////            'last_name',
//            'email:email',
//
//            [
//                'attribute' => 'role',
//                'value' => 'role',
//            ],
////            'user_id',
//            //'image',
//
//            [
//                    'class' => 'yii\grid\ActionColumn',
//                'template'=>'{view} {update} {delete}',
//                'header'=>'Actions',
//                'visibleButtons'=>[
//                    'update' => function ($model) {
//                        return \Yii::$app->user->can('employeeUpdate', ['employee' => $model]);
//                    },
//                     'delete' => function ($model) {
//                        return \Yii::$app->user->can('employeeDelete', ['employee' => $model]);
//                    },
//                    ],
//
//                'buttons'=>[
//                    'view'=>function($url, $model){
//                        return Html::a(FAS::icon('eye'), $url,[
//                                'title' => Yii::t('app', 'View')
//                        ]);
//                    },
//                    'update'=>function($url, $model){
//                        return Html::a(FAS::icon('edit'), $url,[
//                            'title' => Yii::t('app', 'Update')
//                        ]);
//                    },
//                    'delete'=>function($url, $model){
//                        return Html::a(FAS::icon('trash'), $url,aq);
//                    },
//                ],
//
//
//
//            ],
//        ],
//    ]); ?>

    <div class="container">
        <div class="row ">
            <?php foreach ($employees as $employee):?>
                <div class="col-md-3  d-flex justify-content-center h-100 align-self-center" style="min-height: 400px; min-width: 100px">
                    <div class="card mb-4 border-2 shadow-lg" style="width: 18rem;">
                        <img class="card-img-top" src="<?= Employee::findOne(['employee_id'=>$employee['employee_id']])->getImage()?>" alt="image" style="width:100%">
                        <div class="card-body">
                            <h5 class="card-title"><?= Employee::findOne(['employee_id'=>$employee['employee_id']])->getFullname()?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= Employee::findOne(['employee_id'=>$employee['employee_id']])->getRole()?></h6>
                                <p class="card-text">Contact Email: <?= $employee['email']?></p>
                            <div class="text-right">
                                    <?= Html::a('See Productivity', ['view', 'id'=>$employee['employee_id']], ['class' => 'btn m-1 btn-outline-info font-weight-bold']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach;?>
        </div>
    </div>

</div>
