<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Employee */
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->title = 'Update Employee: ' . $model->first_name. " " . $model->last_name;

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="employee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
