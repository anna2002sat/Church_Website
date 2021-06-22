<?php

use rmrevin\yii\fontawesome\FAS;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap4;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Project */

$this->title = $model->title;
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['index', 'isMyProjects'=>true]];
}
else{
    $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="project-view">

    <div class="text-left pt-0">
        <h1 class="display-4 container " style="font-family: 'Algerian'"><?= Html::encode($this->title)?> </h1>
        <h4 class="text-muted">Manager: <?= Html::encode($model->author->fullname) ?></h4>
        <div class="row">
            <div class="col-6 text-center">
                <img src="<?= $model->getImage()?>" class="w-100" style="border-radius: 2px; ">
                <br>
                <?if (Yii::$app->user->can('updateProject', ['project'=>$model])):?>
                <?= Html::a('Change image', ['update-image', 'id'=>$model->project_id, 'isMyProjects'=>$isMyProjects], ['class' => 'btn btn-warning mt-3 w-75 text-center ']) ?>
                <? endif;?>
            </div>
            <div class="col-6  align-self-center">
                <div class="mt-2 text-center justify-content-end" id="sponsored"></div>
                <p class="lead mt-2"><?= $model->description?></p>
                <div class="text-center row">
                    <?if (Yii::$app->user->can('updateProject', ['project'=>$model])):?>
                        <?= Html::a('Update Project', ['update', 'id' => $model->project_id, 'isMyProjects'=>$isMyProjects], ['class' => 'btn btn-primary col-3 m-1']) ?>
                        <?= Html::a('Delete Project', ['delete', 'id' => $model->project_id, 'isMyProjects'=>$isMyProjects], [
                            'class' => 'btn btn-danger col-3 m-1',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a('Add Task', ['task/create', 'project_id'=>$model->project_id, 'isMyProjects'=>$isMyProjects], ['class' => 'btn btn-success col-2 m-1']) ?>
                    <?endif;?>
                    <?if (Yii::$app->user->can('Employee')):?>
                        <?= Html::a('See All Tasks', ['/task/index', 'project_id'=>$model->project_id, 'isMyProjects'=>$isMyProjects], ['class' => 'btn btn-info col-3 m-1']) ?>
                    <? endif;?>
                </div>
            </div>
        </div>
        <hr class="my-4 align-self-center">

        <? if($model->video_url):?>
            <div style="min-height: 250px" class="pr-0 row mt-2">
                <div class="col-sm-2"></div>
                <iframe  style="width: 100%; height: 100%; border-radius: 2px; min-height: 500px"
                         class="align-self-center p-0 col-sm-8 w-100 h-100 "
                         src="<?=$model->video_url?>"
                         allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                         allowfullscreen>
                </iframe>
                <div class="col-sm-2"></div>

            </div>
        <? endif;?>
    </div>

    <? if ($notEmpty):?>
        <h1 class="container text-center mt-5" style="font-family: 'Algerian'">Summary by project</h1>
    <div class="row">
        <!--Completion of project    -->
            <div class="col">
                <div id="completionChart" style="width: 500px; height: 400px;"></div>
            </div>
        <!--Task`s overDue correlation   -->
            <div class="col" style="color: #28a745">
                <div id="overDueChart" style="width: 500px; height: 400px;"></div>
            </div>
        <!--Task`s statuses correlation   -->
            <div class="col">
                <div id="statusChart" style="width: 500px; height: 400px;"></div>
            </div>
        <!--Gender correlation    -->
        <div class="col">
            <div id="genderChart" style="width: 500px; height: 400px;"></div>
        </div>
        </div>
    <? endif;?>
</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        if (<?= $notEmpty?>){
        /////////////////////////////
        var completion = google.visualization.arrayToDataTable([
            ['Completion', 'Number of tasks'],
            ['Completed', <?= $completionChart['Completed']?>],
            ['Not Completed', <?= $completionChart['Not Completed']?>],
        ]);
        var optionsCompletion = {
            title: 'Project Completion:',
            pieHole: 0.4,
            slices: {
                0: {color: '#28a745'},
                1: {color: 'red'}
            }
        };
        var completionChart = new google.visualization.PieChart(document.getElementById('completionChart'));
        completionChart.draw(completion, optionsCompletion);

        /////////////////////////////
        var overDue = google.visualization.arrayToDataTable([
            ['Project Performance', 'Number of tasks'],
            ['Not Overdue', <?= $overDueChart['Not overDue']?>],
            ['Behind Deadline', <?= $overDueChart['overDue']?>],
        ]);
        var optionsOverDue = {
            title: 'Behind Deadlines',
            slices: {
                0: {color: '#28a745'},
                1: {color: 'red'}
            }
        };
        var overDueChart = new google.visualization.PieChart(document.getElementById('overDueChart'));
        overDueChart.draw(overDue, optionsOverDue);

        /////////////////////////////
        var statuses = google.visualization.arrayToDataTable([
            ['Status', 'Tasks in it'],
            ['ToDo', <?= $statusChart['ToDo']?>],
            ['In Progress', <?= $statusChart['InProgress']?>],
            ['To Verify', <?= $statusChart['ToVerify']?>],
            ['Completed', <?= $statusChart['Completed']?>],
        ]);
        var optionsStatus = {
            title: 'Status correlation',
            is3D: true,
            slices: {
                0: {color: 'blue'},
                1: {color: 'orange'},
                2: {color: 'deeppink'},
                3: {color: '#28a745'},
            }
        };
        var statusChart = new google.visualization.PieChart(document.getElementById('statusChart'));
        statusChart.draw(statuses, optionsStatus);

        /////////////////////////////
        var gender = google.visualization.arrayToDataTable([
            ['Gender', 'Number of people'],
            ['Males', <?= $genderChart['males']?>],
            ['Females', <?= $genderChart['females']?>],
        ]);
        var optionsGender = {
            title: 'Gender correlation',
            is3D: true,
            slices: {
                0: {color: 'blue'},
                1: {color: 'deeppink'}
            }
        };
        var genderChart = new google.visualization.PieChart(document.getElementById('genderChart'));
        genderChart.draw(gender, optionsGender);
    }

/////////////////////////////
        if(<?= $model->collected_sum > 0 || $model->needed_sum > 0 ?>) {
            var sponsored = google.visualization.arrayToDataTable([
                ['Sponsored', 'Amount'],
                ['Needed', <?= $model->needed_sum - $model->collected_sum > 0 ? $model->needed_sum - $model->collected_sum : 0?>],
                ['Collected', <?= $model->collected_sum?>]
            ]);
            var optionsSponsored = {
                title: 'Sponsored:',
                pieHole: 0.4,
                slices: {
                    0: {color: 'lightgoldenrodyellow'},
                    1: {color: '#28a745'},
                },
                pieSliceTextStyle: {
                    color: 'black',
                },
            };
            var sponsoredChart = new google.visualization.PieChart(document.getElementById('sponsored'));
            sponsoredChart.draw(sponsored, optionsSponsored);
        }

    }
</script>