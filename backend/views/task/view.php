<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */

$this->title = $model->title;
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['project/index', 'isMyProjects'=>true]];
}
else{
    if(!$isMyTasks)
        $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
}
if($isMyTasks){
    $this->params['breadcrumbs'][] = ['label' => 'My Tasks', 'url' => ['/task/index', 'isMyProjects'=>false, 'isMyTasks'=>true]];
}
else {
    $this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['project/view', 'id' => $model->project_id, 'isMyProjects' => $isMyProjects]];
    $this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index', 'project_id'=>$model->project_id, 'isMyProjects'=>$isMyProjects]];
}

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

    <h1 class="container text-center" style="font-family: 'Algerian'"><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <div class="text-center row pb-5">

        <? if (Yii::$app->user->can('updateProject', ['project' => $model->project])):?>
            <?= Html::a('Update', ['task/update', 'id' => $model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks], ['class' => 'btn btn-primary col m-2']) ?>
        <? elseif (Yii::$app->user->can('updateTaskStatus', ['task' => $model])):?>
            <?= Html::a('Update status', ['task/update', 'id' => $model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks], ['class' => 'btn btn-primary col m-2']) ?>
        <? else:?>
            <?=Html::a('Apply for the task', Url::to(['apply', 'task_id'=>$model->task_id,
                'isMyProjects'=>$isMyProjects]), ['class'=>'btn btn-outline-info col m-2'])?>
        <? endif;?>


        <? if (\Yii::$app->user->can('updateProject', ['project' => $model->project])):?>
        <?= Html::a('Delete', ['task/delete', 'id' => $model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks], [
            'class' => 'btn btn-danger col m-2',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?endif;?>
        <?= Html::a('See Doers', ['doers', 'task_id' => $model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks], ['class' => 'btn btn-info col m-2']) ?>
    </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'start',
            'finish',
            'deadline',
            'status.name',
            [
                'attribute' => 'project.title',
                'format'=>'raw',
                'value'=>Html::a($model->project->title, ['project/view', 'id' => $model->project_id, 'isMyProjects'=>$isMyProjects]),
            ],
        ],
    ]) ?>

</div>
