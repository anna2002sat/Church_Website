<?php

$this->title = 'See doers';

$this->params['breadcrumbs'][] = ['label' => 'My Tasks', 'url' => ['task-index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['task-view', 'id'=>$model->task_id]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<?php

use frontend\models\Employee;
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
    'id' => 'doer'
]);?>

<?if (Yii::$app->user->can('updateProject', ['project' => $model->project])):?>
    <?= $form->field($doer, 'doer_id')->dropDownList(
        ArrayHelper::map($free_employees, 'employee_id', 'fullname'),  [
    'prompt'=>'Choose new doer:',
    'id' => 'field-doer-id',
    'onchange' => '$("#doer").submit()']);?>
<?php endif;?>

<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'dataProvider' => $doers,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'full_name',
            'value' => 'employee.fullname',
        ],
        [
            'attribute' => 'email',
            'value' => 'employee.email',
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
                    if (Yii::$app->user->can('employeeUpdate', ['employee' => $model->employee])){
                    return Html::a(FAS::icon('trash'), ['delete_doer?'.'doer='.$model->employee_id.'&task='.$model->task_id],[
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


