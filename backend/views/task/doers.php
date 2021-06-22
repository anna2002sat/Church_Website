<?php

$this->title = 'See doers';
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['project/index', 'isMyProjects'=>true]];
}
else{
    if(!$isMyTasks)
        $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['project/index']];
}
if($isMyTasks){
    $this->params['breadcrumbs'][] = ['label' => 'My Tasks', 'url' => ['/task/index', 'isMyProjects'=>false, 'isMyTasks'=>true]];
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['task/view', 'id'=>$model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks]];
}
else {
    $this->params['breadcrumbs'][] = ['label' => $model->project->title, 'url' => ['/project/view', 'id' => $model->project->project_id, 'isMyProjects'=>$isMyProjects]];
    $this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index', 'project_id'=>$model->project_id, 'isMyProjects'=>$isMyProjects]];
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['task/view', 'id'=>$model->task_id, 'isMyProjects'=>$isMyProjects]];
}

$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<?php

use frontend\models\Employee;
use frontend\models\Project;
use rmrevin\yii\fontawesome\FAS;

use yii\bootstrap\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

?>

<?php Pjax::begin([
    'id' => 'doer-container',

]); ?>
<?if (Yii::$app->user->can('updateProject', ['project' => $model->project])):?>

    <?php $form = ActiveForm::begin([
        'options' => ['data' => ['pjax' => true],],
        'id' => 'doer'
    ]);?>
    <?= $form->field($doer, 'doer_id')->dropDownList(
        ArrayHelper::map($free_employees, 'employee_id', 'fullname'),  [
    'prompt'=>'Choose new doer:',
    'id' => 'field-doer-id',
    'onchange' => '$("#doer").submit()']);?>
    <?php ActiveForm::end(); ?>
<?php endif;?>



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
                'delete'=>function($url, $model) use ($isMyProjects, $isMyTasks){
                    if (Yii::$app->user->can('employeeUpdate', ['employee' => $model->employee])
                    || Yii::$app->user->can('updateProject', ['project' =>Project::findOne(['project_id'=>$model->task->project_id])->project_id])){
                        return Html::a(FAS::icon('trash'), ['delete_doer', 'doer'=>$model->employee_id,'task'=>$model->task_id, 'isMyProjects'=>$isMyProjects, 'isMyTasks'=>$isMyTasks ],[
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



