<?php

use frontend\models\Salary;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(); ?>

<?= $form->field($model, 'salary[]') ?>
<?= $form->field($model, 'salary[]') ?>
<?= $form->field($model, 'salary[]') ?>


<div class="form-group">
    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>
<!--<form>-->
<!--    <div class="mb-3">-->
<!--        <label for="value1" class="form-label">Value №1</label>-->
<!--        <input type="number" class="form-control" id="value1" name="salary[]" placeholder="0" value="0">-->
<!--    </div>-->
<!--    <div class="mb-3">-->
<!--        <label for="value2" class="form-label">Value №2</label>-->
<!--        <input type="number" class="form-control" id="value2" name="salary[]" placeholder="0" value="0">-->
<!--    </div>-->
<!--    <div class="mb-3">-->
<!--        <label for="value3" class="form-label">Value №3</label>-->
<!--        <input type="number" class="form-control" id="value3" name="salary[]" placeholder="0" value="0">-->
<!--    </div>-->
<!--    <div class="mb-3">-->
<!--        <label for="value4" class="form-label">Value №4</label>-->
<!--        <input type="number" class="form-control" id="value4" name="salary[]" placeholder="0" value="0">-->
<!--    </div>-->
<!--    <div class="mb-5">-->
<!--        <label for="value1" class="form-label">Value №5</label>-->
<!--        <input type="number" class="form-control" id="value5"name="salary[]" placeholder="0" value="0">-->
<!--    </div>-->
<!--    <button  class="btn btn-primary" onclick="count(this)">Count</button>-->
<!--</form>-->

<!---->
<div id="result"></div>
<!---->
<script>
    function count(elem) {
        deleteChild("result");
        elem.disabled = true;

        var values = $("input[name='salary']").map(function(){return $(this).val();}).get();

        $.ajax({
            url: '/salary/count',
            method: "POST",
            data: {
                "values": values
            },
            success: function (response) {
                let result=response.split('*');
                let messageText = "Your salary = " + result[0];
                let messageStatus = 'success';
                if (response === "failure") {
                    messageStatus = 'danger';
                    messageText = 'Помилка!';
                }
                $("#result").append('<div id="errors" class=" w-100 align-self-center alert alert-' + messageStatus + '" role="alert">'
                    + messageText + '</div>');

                elem.disabled = false;
            }
        });
    }
        function deleteChild(idParent){
            let elem= document.getElementById(`${idParent}`);
            if(elem.childElementCount != 0) {
                let count =elem.childElementCount;
                for (let i = 0; i < count; i++)
                    elem.firstElementChild.remove();
            }
        }
</script>