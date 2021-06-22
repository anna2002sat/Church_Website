
<?php

use kartik\rating\StarRating;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Employee */
if (Yii::$app->user->can('employeeUpdate', ['employee' => $model])) {
    $this->title = 'Profile of ' . $model->first_name . " " . $model->last_name;
}
else
    $this->title = $model->first_name . " " . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">

    <h1 class="text-center mb-5" style="font-family: 'Algerian'"><?= Html::encode($this->title) ?></h1>
    <div class="container">
        <div class="row container">
            <div class="col-sm-4 text-center">
                <div class="text-center w-100">

                    <?=Html::img($model->getImage(), ['class'=>'w-100']);?>

                    <br><br>
                    <?  if (Yii::$app->user->can('employeeUpdate', ['employee'=>$model])):?>
                        <?= Html::a('Update Image', ['update-image', 'id' => $model->employee_id], ['class' => 'btn btn-outline-primary w-100']) ?>
                    <?endif;?>
                </div>
            </div>
            <div class="col-sm-8">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'first_name',
                        'last_name',
                        'email:email',
                        'gender',
                        'about'
                    ],
                ]) ?>
                <?  if (Yii::$app->user->can('employeeUpdate', ['employee'=>$model])):?>
                <div class="row ">
                    <p class="col  text-left"><?=Html::a('Edit info', ['update', 'id' => $model->employee_id], ['class' => 'btn btn-outline-info  w-100']);?></p>
                    <?if(!($model->user_id==Yii::$app->user->getId() && Yii::$app->user->can('Admin'))):?>
                    <p class="col  text-right"><?=Html::a('Delete my employee account', ['delete', 'id' => $model->employee_id],
                            ['class' => 'btn btn-outline-danger w-100 ',
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method'=>'post']);?></p>
                    <? endif;?>

                <?endif;?>
                </div>
            </div>
        </div>

        <? if ($empty):?>
            <h1 class="container text-center" style="font-family: 'Algerian'">Productivity</h1>
            <div class="row ">

                <!--Completion of done tasks    -->
                <div class="col ">
                    <div id="completionChart" style="width: 500px; height: 400px;"></div>
                </div>
                <!--Task`s overDue correlation   -->
                <div class="col " style="color: #28a745">
                    <div id="overDueChart" style="width: 500px; height: 400px;"></div>
                </div>
                <!--Task`s statuses correlation   -->
                <div class="col ">
                    <div id="statusChart" style="width: 500px; height: 400px;"></div>
                </div>
                <!--Gender correlation    -->
                <div class="col ">
                    <div id="genderChart" style="width: 500px; height: 400px;"></div>
                </div>
            </div>
        <? endif;?>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="http://code.jquery.com/jquery-1.11.3.min.js" charset="utf-8"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var gender = google.visualization.arrayToDataTable([
            ['Gender', 'Number of people'],
            ['Males',      <?= $genderChart['males']?>],
            ['Females',     <?= $genderChart['females']?>],
        ]);
        var optionsGender = {
            title: 'People on the same tasks gender',
            is3D: true,
            slices: {
                0: { color: 'blue' },
                1: { color: 'deeppink' }
            }
        };
        var genderChart = new google.visualization.PieChart(document.getElementById('genderChart'));
        genderChart.draw(gender, optionsGender);


        var statuses = google.visualization.arrayToDataTable([
            ['Status', 'Tasks in it'],
            ['ToDo',      <?= $statusChart['ToDo']?>],
            ['In Progress',      <?= $statusChart['InProgress']?>],
            ['To Verify',      <?= $statusChart['ToVerify']?>],
            ['Completed',      <?= $statusChart['Completed']?>],
        ]);
        var optionsStatus = {
            title: 'Status correlation',
            is3D: true,
            slices: {
                0: { color: 'blue' },
                1: { color: 'orange' },
                2: { color: 'deeppink' },
                3: { color: '#28a745' },
            }
        };
        var statusChart = new google.visualization.PieChart(document.getElementById('statusChart'));
        statusChart.draw(statuses, optionsStatus);

        var completion = google.visualization.arrayToDataTable([
            ['Completion', 'Number of tasks'],
            ['Completed',      <?= $completionChart['Completed']?>],
            ['Not Completed',     <?= $completionChart['Not Completed']?>],
        ]);
        var optionsCompletion = {
            title: 'My Performance',
            pieHole: 0.4,
            slices: {
                0: { color: '#28a745' },
                1: { color: 'red' }
            }
        };
        var completionChart = new google.visualization.PieChart(document.getElementById('completionChart'));
        completionChart.draw(completion, optionsCompletion);

        var overDue = google.visualization.arrayToDataTable([
            ['My Performance', 'Number of tasks'],
            ['Not Overdue',     <?= $overDueChart['Not overDue']?>],
            ['Behind Deadline',      <?= $overDueChart['overDue']?>],
        ]);
        var optionsOverDue = {
            title: 'Behind Deadlines',
            slices: {
                0: { color: '#28a745' },
                1: { color: 'red' }
            }
        };
        var overDueChart = new google.visualization.PieChart(document.getElementById('overDueChart'));
        overDueChart.draw(overDue, optionsOverDue);
    }
</script>

