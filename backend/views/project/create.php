<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Project */

$this->title = 'Create Project';
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['index', 'isMyProjects'=>true]];
}
else{
    $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'managers' => $managers,
        'isMyProjects'=>$isMyProjects
    ]) ?>

</div>
