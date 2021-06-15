<?php

use frontend\models\Project;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My projects';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['/project']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">


    <h1 class="container text-center" style="font-family: 'Algerian'"><?= Html::encode($this->title) ?></h1>

    <?if (Yii::$app->user->can('Manager')):?>
    <p class="container text-center">
        <?= Html::a('Create Project', ['project-create'], ['class' => 'btn btn-outline-info w-50', 'style'=>"font-family: 'Algerian'; font-size: x-large;"]) ?>
    </p>
    <? endif;?>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
<!--    --><?//= GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
////            'project_id',
//            'title',
//            'desctiptioin:ntext',
//            'image',
//            'video_url:url',
//            //'author_id',
//            [
//                'attribute' => 'Manager',
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

<div class="container">
    <div class="row">
        <?php foreach ($projects as $project):?>
            <div class="col-md-4 d-flex justify-content-center" style="min-height: 400px;">
                <div class="card mb-4 border-2  shadow-lg" style="width: 18rem;">
                    <?if (Yii::$app->user->can('updateProject', ['project' => Project::findOne(['project_id'=>$project->project_id])])):?>
                        <div class="card-header align-self-end " style="background-color: white">
                            <?= Html::a(FAS::icon('edit')->size('lg'), ['project-update', 'id'=>$project->project_id], ['style' =>'color: info;' ]) ?>
                            <?= Html::a(FAS::icon('trash')->size('lg'), ['project-delete', 'id'=>$project->project_id],
                                ['class' => 'btn btn-close p-0', 'style'=>'color: red;',
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'data-method'=>'post']) ?>
                        </div>
                    <? endif;?>
                    <img class="card-img-top" src="<?= Project::findOne(['project_id'=>$project->project_id])->getImage()?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?=$project->title?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $project->author->first_name?> <?=$project->author->last_name?></h6>
                        <p class="card-text"><?= $project->description?></p>

                        <div class="text-right">
                            <?if (Yii::$app->user->can('updateProject', ['project' => Project::findOne(['project_id'=>$project->project_id])])):?>
                                <?= Html::a('Change image', ['project-update-image', 'id'=>$project->project_id], ['class' => 'btn btn-warning m-1']) ?>
                            <? endif;?>
                            <?= Html::a('More', ['project-view', 'id'=>$project->project_id], ['class' => 'btn btn-primary m-1']) ?>

                        </div>
                    </div>
                </div>
            </div>
        <? endforeach;?>
    </div>
</div>
