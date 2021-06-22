<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\Employee */

use frontend\models\Employee;
use frontend\models\Project;
use frontend\models\Task;
use yii\helpers\Html;

$this->title = 'Messages';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="employee-messages">

    <? if(Yii::$app->user->can('Admin')):?>
    <div class="bg-light p-5 mb-4">

        <h2 class="pb-4" style="font-family: 'Algerian'">New employees accounts to verify:</h2>


        <?if(!$new_employees):?>
            <h5 class="text-muted">There are no new employee requests!!! </h5>
        <? else:?>
            <div class="row">
                <?php foreach ($new_employees as $new_employee):?>
        <div class="col-sm-6 mb-4 align-self-center">
            <div class="card flex-row border-2 shadow-lg">
                <img class=" col-3 card-img-left example-card-img-responsive p-0" src="<?= Employee::findOne(['employee_id'=>$new_employee['employee_id']])->getImage()?>" alt="image" >
                <div class="col-9 card-body align-self-center">
                    <h5 class="card-title"><?= Employee::findOne(['employee_id'=>$new_employee['employee_id']])->getFullname()?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"> <?= $new_employee['email']?></h6>
                    <p class="card-text"> <?= $new_employee['about']?></p>
                    <div class="row text-right justify-content-end">
                        <?= Html::a('Deny', ['delete', 'id'=>$new_employee['employee_id'], 'deny'=>true], [
                            'title' => Yii::t('app', 'Deny access'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to deny access to this employee?'),
                            'data-method'=>'post',
                            'class' => 'btn btn-danger btn-md m-1 w-25']) ?>
                        <?= Html::a('Accept', ['accept', 'user_id'=>$new_employee['user_id']], ['class' => 'btn btn-success  btn-md m-1 w-25 ']) ?>
                    </div>
                </div>
            </div>
        </div>
        <? endforeach;?>
            </div>
        <? endif;?>

    </div>
    <? endif;?>


    <div class="bg-light p-5">
        <h2 class="pb-4" style="font-family: 'Algerian'">New employees to verify for the task :</h2>

        <? if(!$new_doers):?>
            <h5 class="text-muted">There are no new employee requests!!! </h5>
        <? else:?>
            <?php foreach ($new_doers as $new_doer):?>
                <div class="col mb-4 align-self-center">
                    <div class="card flex-row border-2 shadow-lg">
                        <div class="card text-center col-2 h-100 border-0 align-self-center">
                            <img class="card-img-left example-card-img-responsive p-0 mb-2" src="<?=$new_doer['employee_img']?>" alt="image" >
                            <h5 class="card-title"> <?= Html::a($new_doer['doer'], ['employee/view', 'id'=>$new_doer['employee_id']])?></h5>
                        </div>
                        <div class="card text-center col-2 h-100 border-0 align-self-center">
                            <img class="card-img-left example-card-img-responsive p-0 mb-2" src="<?=$new_doer['project_img']?>" alt="image" >
                            <h5 class="card-title"> <?= Html::a($new_doer['project_name'], ['project/view', 'id'=>$new_doer['project_id']])?></h5>
                        </div>

                        <div class="col-8 card-body align-self-center">
                            <h5 class="card-title">Task Title: <?= Html::a($new_doer['task_name'], ['task/view', 'id'=>$new_doer['task_id'], 'isMyProjects'=>false])?></h5>
                            <div class="row text-right justify-content-end">
                                <?= Html::a('Deny', ['/task/delete_doer', 'doer'=>$new_doer['employee_id'], 'isDeny'=>true, 'task'=>$new_doer['task_id']], [
                                    'title' => Yii::t('app', 'Deny access'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to deny access to this employee?'),
                                    'data-method'=>'post',
                                    'class' => 'btn btn-danger btn-md m-1 w-25']) ?>
                                <?= Html::a('Accept', ['task/accept', 'doer_id'=>$new_doer['employee_id'], 'task_id'=>$new_doer['task_id']], ['class' => 'btn btn-success  btn-md m-1 w-25 ']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach;?>
        <? endif;?>

    </div>


</div>
