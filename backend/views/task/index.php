<?php

use frontend\models\TaskEmployee;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Tasks for: '.$model->title;
if($isMyTasks){
    $this->title = 'My tasks';
}
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['project/index', 'isMyProjects'=>true]];
}
else{
    if(!$isMyTasks)
        $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['project/index']];
}
if(!$isMyTasks)
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['project/view', 'id'=>$model->project_id, 'isMyProjects'=>$isMyProjects]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">
    <h1 class="container text-center" style="font-family: 'Algerian'"><?= Html::encode($this->title) ?></h1>

    <? if (Yii::$app->user->can('updateProject', ['project'=>$model])):?>
        <p class="text-center">
            <?= Html::a('Add Task', ['task/create', 'project_id'=>$model->project_id,
                    'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks],
                ['class' => 'btn btn-outline-success w-50', 'style'=>"font-family: 'Algerian'; font-size: large;"]) ?>
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
            'deadline',
            'finish',
            [
                'attribute' => 'status_name',
                'value' => 'status.name',
            ],
            [
                'label' => 'Apply',
                'format' => 'raw',
                 'visible' => (!(Yii::$app->user->can('updateProject', ['project' => $dataProvider->models->project]))),
                'value' => function($model) use($isMyProjects){
                    $message = $model->is_in_task;
                        if($message==false)
                        return '<div class="text-center">'.Html::a('Apply for the task', Url::to(['apply', 'task_id'=>$model->task_id,
                            'isMyProjects'=>$isMyProjects]), ['class'=>'btn btn-outline-info']).'</div>';
                    else
                        if($message=='toVerify'){
                            return "<p class='text-center text-success'>You have applied for the task!</p>";
                        }
                        else
                              return "<p class='text-center text-success'>You are in business</p>";
                },



            ],
//            'project.title',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {delete} {doers}',
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
                    'view'=>function($url, $model) use ($isMyProjects, $isMyTasks){
                        return Html::a(FAS::icon('eye'), ['task/view', 'id'=>$model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks],[
                            'title' => Yii::t('app', 'View')
                        ]);
                    },
                    'update'=>function($url, $model) use ($isMyProjects, $isMyTasks){
                        return Html::a(FAS::icon('edit'), ['task/update', 'id'=>$model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks],[
                            'title' => Yii::t('app', 'Update')
                        ]);
                    },
                    'delete'=>function($url, $model) use ($isMyProjects, $isMyTasks){
                        return Html::a(FAS::icon('trash'), ['task/delete', 'id'=>$model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks],[
                            'title' => Yii::t('app', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method'=>'post',
                        ]);
                    },
                    'doers'=>function($url, $model) use ($isMyProjects, $isMyTasks){
                        return Html::a(FAS::icon('users'),['task/doers', 'task_id'=>$model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks],[
                            'title' => Yii::t('app', 'Doers')
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
