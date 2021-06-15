<link rel="stylesheet" href="/frontend/web/css/about.css">
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!--CONTACT INFORMATION-->
    <div class="bg-light w-100">
        <div class="mt-5 mb-5">
            <h2 class="title text-center mt-3">Контактні дані</h2>
            <div class=" row mb-5 row-cols-md-4">
                <div class="col-3 text-center m-0">
                    <a href="https://m.youtube.com/channel/UCJXEbTgU6NbCd4x6Z94bsyw">
                        <i class="icon fab fa-youtube " style="color: red"></i></br>
                        <p class="mt-2">Церковний Youtube</p>
                    </a>
                </div>
                <div class="col-3 text-center m-0">
                    <a href="https://msng.link/o/?380971449968=vi">
                        <i class="fab fa-viber icon" style="color: #7557f3"></i></br>
                        <p class="mt-2">Пасторський Viber</p>
                    </a>
                </div>
                <div class="col-3 text-center m-0">
                    <a href="https://instagram.com/molodizhka_za_zirkoy?igshid=tnl4lwszzayw">
                <span class="instagram ">
                <i class=" fab fa-instagram icon" style="font-size: 60px;color: white "></i><br>
                    </span>

                        <p class="mt-2">Церковний Instagram</p>
                    </a>
                </div>
                <div class="col-3 text-center m-0">
                    <a href="mailto:tserkvazazirkoy@gmail.com">
                        <i class="far fa-envelope icon" style="color: blue"></i></br>
                        <p class="mt-2">Церковний Gmail</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
