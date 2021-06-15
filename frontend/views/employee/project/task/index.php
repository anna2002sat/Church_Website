<?php

use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label' => 'My projects', 'url' => ['projects']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['project-view', 'id' => $model->project_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1 class="container text-center" style="font-family: 'Algerian'">Tasks for <?=$model->title?></h1>
    <?if (Yii::$app->user->can('Manager')):?>
        <p class="text-center">
            <?= Html::a('Add Task', ['project-task-create', 'project_id'=>$model->project_id], ['class' => 'btn btn-outline-success w-50', 'style'=>"font-family: 'Algerian'; font-size: large;"]) ?>
        </p>
    <? endif;?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'task_id',
            'title',
            'start',
//            'finish',
            'deadline',
            [
                'attribute' => 'status_name',
                'value' => 'status.name',
            ],
//            'project.title',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {delete}',
                'header'=>'Actions',
                'visibleButtons'=>[
                    'delete' => function ($model) {
                        return \Yii::$app->user->can('updateProject', ['project' => $model->project]);
                    },
                    'update' => function ($model) {
                        return \Yii::$app->user->can('updateTask', ['task' => $model]);
                    },
                ],
                'buttons'=>[
                    'view'=>function($url, $model){
                        return Html::a(FAS::icon('eye'), ['project-task-view', 'id'=>$model->task_id],[
                            'title' => Yii::t('app', 'View')
                        ]);
                    },
                    'update'=>function($url, $model){
                        return Html::a(FAS::icon('edit'), ['project-task-update', 'id'=>$model->task_id],[
                            'title' => Yii::t('app', 'Update')
                        ]);
                    },
                    'delete'=>function($url, $model){
                        return Html::a(FAS::icon('trash'), ['task-delete', 'id'=>$model->task_id],[
                            'title' => Yii::t('app', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method'=>'post',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
