<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Project */

$this->title = 'Update Project: ' . $model->title;
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['index', 'isMyProjects'=>true]];
}
else{
    $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->project_id,'isMyProjects'=>$isMyProjects ]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="project-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'managers' => $managers,
        'isMyProjects'=>$isMyProjects
    ]) ?>

</div>
