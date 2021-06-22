<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */

$this->title = 'Create Task';
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['project/index', 'isMyProjects'=>true]];
}
else{
    if(!$isMyTasks)
        $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['project/index']];
}
if($isMyTasks)
    $this->params['breadcrumbs'][] = ['label' => 'My Tasks', 'url' => ['/task/index', 'isMyProjects'=>false, 'isMyTasks'=>true]];
else
    $this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['project/view', 'id'=>$model->project_id, 'isMyProjects'=>$isMyProjects]];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'isMyProjects'=>$isMyProjects,
        'isMyTasks'=>$isMyTasks,
    ]) ?>

</div>
