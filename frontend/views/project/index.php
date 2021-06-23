<?php

use frontend\models\Project;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ($isMyProjects ? 'My Projects':'Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">


    <h1 class="container text-center" style="font-family: 'Algerian'"><?= Html::encode($this->title) ?></h1>


    <div class="row text-center">
        <?if (Yii::$app->user->can('Employee') && !$isMyProjects):?>
        <p class="container col-6">
            <?= Html::a('My Projects', ['index', 'isMyProjects'=>true], ['class' => 'btn btn-outline-primary w-75', 'style'=>"font-family: 'Algerian'; font-size: x-large;"]) ?>
        </p>
        <? endif;?>
    </div>

        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
<!--    --><?//= GridView::widget([
//        'dataProvider' => $projects,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
////            'project_id',
//            'title',
//            'description',
//            'image',
//            'video_url:url',
//            //'author_id',
//            [
//                'attribute' => 'manager',
//                'value' => 'author.fullname',
//            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template'=>'{view} {update} {delete}',
//                'header'=>'Actions',
//                'visibleButtons'=>[
//                    'update' => function ($model) {
//                        return \Yii::$app->user->can('updateProject', ['project' => $model]);
//                    },
//                    'delete' => function ($model) {
//                        return \Yii::$app->user->can('updateProject', ['project' => $model]);
//                    },
//                ],
//                'buttons'=>[
//                    'view'=>function($url, $model){
//                        return Html::a(FAS::icon('eye'), ['view', 'id'=>$model->project_id],[
//                            'title' => Yii::t('app', 'View')
//                        ]);
//                    },
//                    'update'=>function($url, $model){
//                        return Html::a(FAS::icon('edit'), ['update', 'id'=>$model->project_id],[
//                            'title' => Yii::t('app', 'Update')
//                        ]);
//                    },
//                    'delete'=>function($url, $model){
//                        return Html::a(FAS::icon('trash'), ['delete', 'id'=>$model->project_id],[
//                            'title' => Yii::t('app', 'Delete'),
//                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
//                            'data-method'=>'post',
//                        ]);
//                    },
//                ],
//            ],
//        ],
//    ]); ?>

<!--    Authors correlation    -->
<!--    <div class="col">-->
<!--        <div id="authorsChart" style="width: 500px; height: 400px"></div>-->
<!--    </div>-->


</div>


<div class="container">
    <div class="row align-self-center">
        <?php foreach($projects as $project):?>
            <div class="col-md-4 h-100 d-flex justify-content-center align-self-center" style="min-height: 400px; min-width: 100px">
                <div class="card card-block mb-4 border-2  shadow-lg" style="width: 18rem;">
                    <img class="card-img-top" src="<?= Project::findOne(['project_id'=>$project->project_id])->getImage()?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?=$project->title?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $project->author->first_name?> <?=$project->author->last_name?></h6>
                        <p class="card-text"><?= $project->description?></p>

                        <div class="text-right">
                            <?= Html::a('More info', ['view', 'isMyProjects'=>$isMyProjects, 'id'=>$project->project_id], ['class' => 'btn btn-outline-info font-weight-bold']) ?>

                        </div>
                    </div>
                </div>
            </div>
        <? endforeach;?>
    </div>
</div>

<!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
<!--<script type="text/javascript">-->
<!--    google.charts.load('current', {'packages':['corechart']});-->
<!--    google.charts.setOnLoadCallback(drawChart);-->
<!---->
<!--    function drawChart() {-->
<!---->
<!--        var authors = google.visualization.arrayToDataTable([-->
<!--            ['Author', 'Number of projects'],-->
<!--            --><?// foreach ($authorsStat as $author):?>
<!--                ["--><?//=$author['author']['first_name']." ".$author['author']['last_name']?><!--//",  --><?//= $author['count']?><!--//],-->
<!--            --><?// endforeach;?>
<!--        ]);-->
<!--        var optionsAuthors = {-->
<!--            title: 'Author correlation',-->
<!--            is3D: true,-->
<!--        };-->
<!--        var authorsChart = new google.visualization.PieChart(document.getElementById('authorsChart'));-->
<!--        authorsChart.draw(authors, optionsAuthors);-->
<!--    }-->
<!--</script>-->