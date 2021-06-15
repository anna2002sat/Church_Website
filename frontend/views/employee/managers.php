<?php

$this->title = 'Manage Managers';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php

use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

?>

<?php Pjax::begin([
    'id' => 'doer-container'
]); ?>
<?php $form = ActiveForm::begin([
    'options' => ['data' => ['pjax' => true],],
    'id' => 'manager'
]);?>

<?if (Yii::$app->user->can('Admin')):?>
    <?= $form->field($manager, 'doer_id')->dropDownList(
        ArrayHelper::map($free_employees, 'user_id', 'fullname'),  [
    'prompt'=>'Choose new manager:',
    'id' => 'field-doer-id',
    'onchange' => '$("#manager").submit()']);?>
<?php endif;?>

<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'dataProvider' => $managers,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'full_name',
            'value' => 'fullname',
        ],
        [
            'attribute' => 'email',
            'value' => 'email',
        ],

        ['class' => 'yii\grid\ActionColumn',
            'template'=>'{view} {delete}',
            'header'=>'Actions',
            'buttons'=>[
                'view'=>function($url, $model){
                    return Html::a(FAS::icon('eye'), ['/employee/view', 'id'=>$model->employee_id],[
                        'title' => Yii::t('app', 'View')
                    ]);
                },
                'delete'=>function($url, $model){
                    if (Yii::$app->user->can('Admin')){
                    return Html::a(FAS::icon('trash'), ['delete-manager', 'user_id' => $model->user_id],[
                        'title' => Yii::t('app', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method'=>'post',
                    ]);
                    }
                    return '';
                },
            ],
        ],
    ],
]); ?>
<?php Pjax::end(); ?>


