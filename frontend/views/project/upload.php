<?php

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
            <?= Html::a('Cancel', ['view', 'id' => $project->project_id], ['class' => 'btn btn-primary col-5']) ?>
            <div class="col-1"></div>
            <button class="btn btn-success col-5">Submit</button>
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

