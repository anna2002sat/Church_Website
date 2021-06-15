<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Project */

$this->title = 'Create Task';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label' => 'My projects', 'url' => ['projects']];
$this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['project-view', 'id' => $model->project_id]];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('/employee\task\_form', [
        'model' => $model,
        'managers' => $managers,
    ]) ?>

</div>
