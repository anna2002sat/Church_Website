<?php

use frontend\models\Employee;
use frontend\models\Project;
use frontend\models\Status;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */
$this->title = 'Update Task';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label' => 'My projects', 'url' => ['projects']];
$this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['project-view', 'id' => $model->project_id]];
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['project-tasks', 'project_id'=>$model->project_id]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['project-task-view', 'id' => $model->task_id]];

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="task-form">

        <?php $form = ActiveForm::begin(); ?>
        <? if (Yii::$app->user->can('updateProject', ['project'=>$model->project])): ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'start')->widget(\yii\jui\DatePicker::classname(), [
                'dateFormat' => 'yyyy-MM-dd',
            ]) ?>

            <?= $form->field($model, 'deadline')->widget(\yii\jui\DatePicker::classname(), [
                'dateFormat' => 'yyyy-MM-dd',
            ]) ?>
            <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(Status::find()->asArray()->all(), 'status_id', 'name')) ?>

            <? if (Yii::$app->user->can('Admin', ['project'=>$model->project])):?>
                <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(Project::find()->asArray()->all(), 'project_id', 'title'));?>
            <? else: ?>
                <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(Project::findAll(['author_id'=>(Employee::findOne(['user_id'=>Yii::$app->user->getId()])->employee_id)]), 'project_id', 'title'));?>
            <?endif;?>

        <?elseif (Yii::$app->user->can('updateTaskStatus', ['task'=>$model])): ?>
            <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(Status::find()->where(['!=', 'name', 'Completed'])->asArray()->all(), 'status_id', 'name')) ?>
        <? endif;?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
