<?php

use frontend\models\Project;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Donation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="donation-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <? if(Yii::$app->user->isGuest):?>
            <div class="col">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
        <? endif;?>
        <div class="col">
            <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(
                Project::find()->asArray()->all(), 'project_id', 'title'), ['prompt'=> 'All projects']);?>
        </div>

    </div>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>



    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <div class="form-group text-right">
        <?= Html::submitButton('Donate', ['class' => 'btn btn-success w-25']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
