<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Task */

$this->title = 'Create Task';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['view', 'id'=>$model->project_id]];
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['/project/tasks', 'project_id'=>$model->project_id]];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
