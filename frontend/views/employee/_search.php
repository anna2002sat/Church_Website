<?php

use yii\bootstrap4\ButtonDropdown;
use yii\bootstrap4\Dropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\EmployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="employee-search">

    <fieldset class="border-2 bg-light pr-4 pt-2 pl-4 pb-0 mb-3"  style="border: 2px dodgerblue solid;">
            <div class="row d-flex " style="font-family: v_ALoveOfThunder">
                <div class="mr-1 align-self-center col-4"><button class="btn btn-outline-primary " data-toggle="collapse" data-target="#search" style="font-family: v_ALoveOfThunder">Advanced Search Bar:</button>
                </div>
                <div class=" ml-2 mb-1 col-3 align-self-center">
                    <?=ButtonDropdown::widget([
                        'label' => 'Choose Sort Action',
                        'options'=>['class'=>'btn btn-outline-light'],
                        'dropdown' => [
                            'items' => [
                                ['label' => 'Created asc', 'url' => '?sort=employee_id'],
                                ['label' => 'Created desc', 'url' => '?sort=-employee_id'],
                                ['label' => 'Full Name asc', 'url' => '?sort=full_name'],
                                ['label' => 'Full Name desc', 'url' => '?sort=-full_name'],
                                ['label' => 'Email asc', 'url' => '?sort=email'],
                                ['label' => 'Email desc', 'url' => '?sort=-email'],
                                ['label' => 'Role asc', 'url' => '?sort=role'],
                                ['label' => 'Role desc', 'url' => '?sort=-role'],
                            ],
                        ],
                    ]);?>
                </div>
                <div class="col"></div>
                <?php $form = ActiveForm::begin([
                    'action' => ['index'],
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

        <? if($model['full_name']!=''||$model['email']!=''||$model->role!=''):?>
            <p style="color: red">Please reset a search bar to see all employees...</p>
        <? endif;?>
        <div id="search" class="collapse mt-4" >
            <?php $form = ActiveForm::begin([
                'id'=>'form',
                'action' => ['index'],
                'method' => 'get',
            ]); ?>

            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'full_name') ?></div>
                <div class="col-6">
                    <?= $form->field($model, 'email') ?></div>
            </div>

            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'role') ?></div>
                <div class="col-6 align-self-end">
                    <div class="form-group">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-primary w-25']) ?>
                        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary w-25', 'onclick' => 'window.location.replace(window.location.pathname);']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    </fieldset>

</div>
