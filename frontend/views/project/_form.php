<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(['options'=> ['enctype'=>'multipart/form-data']]); ?>

    <? if(!$model->project_id):?> <!-- if create-->
        <?= $form->field($model, 'image')->fileInput(['value' => '/images/projects/project_placeholder.jpg'])?>
    <? endif;?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'video_url')->textInput(['maxlength' => true]) ?>

    <? if (Yii::$app->user->can('Admin', ['project'=>$model])):?>
        <?if($model->author_id):?>
            <?= $form->field($model, 'author_id')->dropDownList(ArrayHelper::map($managers, 'employee_id', 'fullname'))?>
        <? else:?>
            <?= $form->field($model, 'author_id')->dropDownList(ArrayHelper::map($managers, 'employee_id', 'fullname'),
                ['prompt'=> 'Select manager...'])?>
        <?endif;?>
    <?endif;?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>