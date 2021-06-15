<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;
?>
<?php

$form = ActiveForm::begin(['options'=> ['enctype'=>'multipart/form-data']]) ?>
<div class="row mb-3">
    <div class="col-sm-6">
        <?=Html::img($employee->getImage(),  ['class' => 'w-75 mb-3', 'id'=>'image']);?>
        <?= $form->field($model, 'imageFile')->fileInput(['accept'=>"image/*"]) ?>

        <div class="row container">
            <?= Html::a('Cancel', ['view', 'id' => $employee->employee_id], ['class' => 'btn btn-primary col-5']) ?>
            <div class="col-1"></div>
            <button class="btn btn-success col-5">Submit</button>
        </div>
    </div>
    <div class="col-sm-6"></div>
</div>
<?php ActiveForm::end()?>

