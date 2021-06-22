<?php

use frontend\models\Department;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <? if(!$model->employee_id):?> <!-- if create-->
        <?= $form->field($model, 'image')->fileInput(['value' => '/images/employees/employee_placeholder.jpg'])?>
    <? endif;?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    <?if (Yii::$app->user->can('Admin') || $update):?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <? endif;?>

    <?= $form->field($model, 'gender')->dropDownList([
        'Male' => 'Male',
        'Female' => 'Female',
    ]) ?>
    <?= $form->field($model, 'about')->textarea(['rows' => 6]) ?>
    <div class="form-group">
        <?if($update && !Yii::$app->user->can('Admin')):?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success',
            'title' => Yii::t('app', 'Save changes'),
            'data-confirm' => Yii::t('yii', 'Are you sure you want to save changes? If you have modified your email you will need to confirm it to log in again! '),
            'data-method'=>'post',
        ]) ?>
        <?else:?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <? endif;?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
