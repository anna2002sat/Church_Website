<?php

use frontend\models\Employee;
use frontend\models\Project;
use frontend\models\Status;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */

$this->title = 'Update Task: ' . $model->title;
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['project/index', 'isMyProjects'=>true]];
}
else{
    if(!$isMyTasks)
        $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['project/index']];
}
if($isMyTasks){
    $this->params['breadcrumbs'][] = ['label' => 'My Tasks', 'url' => ['/task/index', 'isMyProjects'=>false, 'isMyTasks'=>true]];
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['task/view', 'id'=>$model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]];
}
else {
    $this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['project/view', 'id'=>$model->project_id, 'isMyProjects'=>$isMyProjects]];
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['task/view', 'id'=>$model->task_id, 'isMyProjects'=>$isMyProjects]];
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="task-form">

        <?php $form = ActiveForm::begin(); ?>
        <? if (Yii::$app->user->can('updateProject', ['project'=>$model->project])): ?>
            <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(Status::find()->asArray()->all(), 'status_id', 'name')) ?>
        <?elseif (Yii::$app->user->can('updateTaskStatus', ['task'=>$model])): ?>
            <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(Status::find()->where(['!=', 'name', 'Completed'])->asArray()->all(), 'status_id', 'name')) ?>
        <? endif;?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
