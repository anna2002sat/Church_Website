<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Employee */

$this->title = 'Create Employee Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
