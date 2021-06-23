<?php

use frontend\controllers\DonationController;
use frontend\models\Donation;
use frontend\models\Project;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DonationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if($my){
    $this->title = 'My Donations';
}
else
    $this->title = 'Donations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="donation-index">

    <h1 class="container text-center mb-3 " style="font-family: 'Algerian'"><?= Html::encode($this->title)?> </h1>

    <p class="text-center">
        <?= Html::a('Donate', ['create'], ['class' => 'btn btn-outline-success w-50 display-3', 'style'=>"font-family: 'Algerian'"]) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div style="max-width: 1310px">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'donation_id',
            'first_name',
            'last_name',
            'phone',
            'amount',
            [
                'attribute' => 'project_id',
                'format'=>'raw',
                'value'=>'purpose',
                'filter'=>ArrayHelper::map(array_merge([['project_id'=>'-1', 'title'=>'Unassigned']],
                        Donation::Purposes()), 'project_id', 'title'),
            ],

            [
                'attribute' => 'comments',
                'contentOptions' => [
                    'style' => [
                        'max-width' => '1000px',
                        'white-space' => 'normal',
                    ],
                ],
            ],
        ],
    ]); ?>
</div>

</div>
