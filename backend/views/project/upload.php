<?php
$this->title = 'Update Project: ' . $project->title;
if($isMyProjects){
    $this->params['breadcrumbs'][] = ['label' => 'My Projects', 'url' => ['index', 'isMyProjects'=>true]];
}
else{
    $this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
}
$this->params['breadcrumbs'][] = ['label' => $project->title, 'url' => ['view', 'id' => $project->project_id,'isMyProjects'=>$isMyProjects ]];
$this->params['breadcrumbs'][] = 'Change image';
use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;
?>
<?php

$form = ActiveForm::begin(['options'=> ['enctype'=>'multipart/form-data']]) ?>
<div class="row mb-3">
    <div class="col-sm-6">
        <?=Html::img($project->getImage(),  ['class' => 'w-75 mb-3', 'id'=>'image']);?>
        <?= $form->field($model, 'image')->fileInput(['id'=>'imageFile']) ?>

        <div class="row container">
            <?= Html::a('Cancel', ['view', 'id' => $project->project_id, 'isMyProjects'=>$isMyProjects], ['class' => 'btn btn-primary col-5']) ?>
            <div class="col-1"></div>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success col-5', 'isMyProjects'=>$isMyProjects]) ?>
        </div>
    </div>
    <div class="col-sm-6"></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<script>
    $(`#imageFile`).change(function(event){
        // console.log(event.value);
    });

</script>
<?php ActiveForm::end()?>

