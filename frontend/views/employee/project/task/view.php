<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label' => 'My projects', 'url' => ['projects']];
$this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['project-view', 'id' => $model->project_id]];
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['project-tasks', 'project_id'=>$model->project_id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-center">
        <? if (Yii::$app->user->can('updateProject', ['project' => $model->project])):?>
            <?= Html::a('Update', ['project-task-update', 'id' => $model->task_id], ['class' => 'btn btn-primary']) ?>
        <? elseif (Yii::$app->user->can('updateTaskStatus', ['task' => $model])):?>
            <?= Html::a('Update status', ['project-task-update', 'id' => $model->task_id], ['class' => 'btn btn-primary']) ?>
        <? endif;?>

        <? if (\Yii::$app->user->can('updateProject', ['project' => $model->project])):?>
        <?= Html::a('Delete', ['task-delete', 'id' => $model->task_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?endif;?>
        <?= Html::a('See Doers', ['project-doers', 'task_id' => $model->task_id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'start',
            'deadline',
            'status.name',
            [
                'attribute' => 'project.title',
                'format'=>'raw',
                'value'=>Html::a($model->project->title, ['project-view', 'id' => $model->project_id]),
            ],
        ],
    ]) ?>

</div>
