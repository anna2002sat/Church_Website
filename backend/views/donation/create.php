<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Donation */

$this->title = 'Make a Donation';
$this->params['breadcrumbs'][] = ['label' => 'Donation', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="donation-create">

    <h1 class="container text-center mb-3" style="font-family: 'Algerian'"><?= Html::encode($this->title)?> </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
