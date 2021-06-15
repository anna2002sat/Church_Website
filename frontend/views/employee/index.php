<?php

use frontend\models\Employee;
use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\web\ForbiddenHttpException;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1 class="container text-center" style="font-family: 'Algerian'"><?= Html::encode($this->title) ?></h1>

    <div class="text-center row">
        <?if (Yii::$app->user->can('Admin')):?>
            <p class="col-6"><?= Html::a('Create a New Employee', ['create'], ['class' => 'btn btn-outline-primary w-75', 'style'=>"font-family: 'Algerian'; font-size: x-large;"]) ?></p>
            <p class="col-6"><?= Html::a('Manage Manager List', ['managers'], ['class' => 'btn btn-outline-primary w-75', 'style'=>"font-family: 'Algerian'; font-size: x-large;"]) ?></p>
        <? endif;?>
        <p class="container  col-6">

        </p>
    </div>



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
//                        return Html::a(FAS::icon('trash'), $url,[
//                            'title' => Yii::t('app', 'Delete'),
//                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
//                            'data-method'=>'post',
//                        ]);
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
                <div class="col-md-3  d-flex justify-content-center" style="min-height: 400px; min-width: 100px">
                    <div class="card mb-4 border-2 shadow-lg" style="width: 18rem;">
                        <?if (Yii::$app->user->can('employeeUpdate', ['employee' => Employee::findOne(['employee_id'=>$employee['employee_id']])])):?>
                            <div class="card-header text-right" style="background-color: white">
                                <?= Html::a(FAS::icon('edit')->size('lg'), ['update', 'id'=>$employee['employee_id']], ['style' =>'color: info;' ]) ?>
                                <?= Html::a(FAS::icon('trash')->size('lg'), ['delete', 'id'=>$employee['employee_id']],
                                    ['class' => 'btn btn-close p-0', 'style'=>'color: red;',
                                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'data-method'=>'post']) ?>
                            </div>
                        <? endif;?>
                        <img class="card-img-top" src="<?= Employee::findOne(['employee_id'=>$employee['employee_id']])->getImage()?>" alt="image" style="width:100%">
                        <div class="card-body">
                            <h5 class="card-title"><?= Employee::findOne(['employee_id'=>$employee['employee_id']])->getFullname()?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= Employee::findOne(['employee_id'=>$employee['employee_id']])->getRole()?></h6>
                            <p class="card-text">Contact Email: <?= $employee['email']?></p>
                            <div class="text-right">
                                <?if (Yii::$app->user->can('employeeUpdate', ['employee' => Employee::findOne(['employee_id'=>$employee['employee_id']])])):?>
                                    <?= Html::a('See Profile', ['view', 'id'=>$employee['employee_id']], ['class' => 'btn btn-primary m-1']) ?>
                                <? else:?>
                                    <?= Html::a('See Productivity', ['view', 'id'=>$employee['employee_id']], ['class' => 'btn btn-info m-1']) ?>
                                <? endif;?>

                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach;?>
        </div>
    </div>

</div>
