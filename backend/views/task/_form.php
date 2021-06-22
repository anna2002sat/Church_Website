<?php

use frontend\models\Employee;
use frontend\models\Project;
use frontend\models\Status;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="task-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start')->widget(\yii\jui\DatePicker::classname(), [
//         'language' => 'ua',
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>
<!--    --><?//= $form->field($model, 'finish')->widget(\yii\jui\DatePicker::classname(), [
////        'language' => 'ua',
//        'dateFormat' => 'yyyy-MM-dd',
//    ]) ?>
    <?= $form->field($model, 'deadline')->widget(\yii\jui\DatePicker::classname(), [
//        'language' => 'UK',
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>


    <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(Status::find()->asArray()->all(), 'status_id', 'name')) ?>

    <? if (Yii::$app->user->can('Admin')):?>
        <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(Project::find()->asArray()->all(), 'project_id', 'title'));?>
    <? else: ?>
        <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(Project::findAll(['author_id'=>(Employee::findOne(['user_id'=>Yii::$app->user->getId()])->employee_id)]), 'project_id', 'title'));?>
    <?endif;?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
