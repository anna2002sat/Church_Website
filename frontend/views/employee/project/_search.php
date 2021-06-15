<?php

use yii\bootstrap4\ButtonDropdown;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ProjectSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-search">

    <fieldset class="border-2 bg-light pr-4 pt-2 pl-4 pb-0 mb-3"  style="border: 2px dodgerblue solid;">
        <div class="row d-flex " style="font-family: v_ALoveOfThunder">
            <div class="mr-1 align-self-center col-4"><button class="btn btn-outline-primary " data-toggle="collapse" data-target="#search" >Advanced Search Bar:</button>
            </div>
            <div class=" ml-2 mb-1 col-3 align-self-center">
                <?=ButtonDropdown::widget([
                    'label' => 'Choose Sort Action',
                    'options'=>['class'=>'btn btn-outline-light'],
                    'dropdown' => [
                        'items' => [
                            ['label' => 'Created asc', 'url' => '?sort=project_id'],
                            ['label' => 'Created desc', 'url' => '?sort=-project_id'],
                            ['label' => 'Title asc', 'url' => '?sort=title'],
                            ['label' => 'Title desc', 'url' => '?sort=-title'],
                            ['label' => 'Manager asc', 'url' => '?sort=manager'],
                            ['label' => 'Manager desc', 'url' => '?sort=-manager'],
                        ],
                    ],
                ]);?>
            </div>
            <div class="col"></div>
            <?php $form = ActiveForm::begin([
                'action' => ['employee/projects'],
                'method' => 'get',
            ]); ?>
            <form class="col-5 align-self-end">
                <div class="form-row m-0 d-flex">
                    <div class="col-8 ">
                        <?= $form->field($model, 'search')->label('General') ?>
                    </div>
                    <div class="col-4 align-self-center mt-3 ">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-primary w-100']) ?>
                    </div>
                </div>
            </form>

            <? ActiveForm::end()?>
        </div>

        <? if($model['title']!=''||$model['description']!=''||$model['manager']!=''||$model['video_url']!=''):?>
            <p style="color: red">Please reset search bar to cancel filtration...</p>
        <? endif;?>
        <div id="search" class="collapse mt-4">
            <?php $form = ActiveForm::begin([
                'action' => ['employee/projects'],
                'method' => 'get',
            ]); ?>
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'title') ?></div>
                <div class="col-6">
                    <?= $form->field($model, 'description') ?></div>
            </div>

            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'video_url') ?></div>
                <div class="col-6"><?= $form->field($model, 'manager') ?></div>
            </div>


            <div class="form-group text-right">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary w-25']) ?>
                <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary w-25', 'onclick' => 'window.location.replace(window.location.pathname);']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </fieldset>

</div>
