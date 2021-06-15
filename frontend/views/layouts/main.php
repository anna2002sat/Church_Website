<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\widgets\Alert;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use frontend\assets\AppAsset;
use yii\helpers\Url;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?\Yii::$app->view->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Url::to(['/frontend/web/images/icon.png'])]);?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-lg navbar-dark bg-primary ',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Projects', 'url' => ['/project/index']],

    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'About', 'url' => ['/site/about']];
        $menuItems[] = ['label' => 'Contact us', 'url' => ['/site/contact']];
        $menuItems[] = ['label' => 'Register', 'url' => ['/user/signup'], 'class'=>'navItem'];
        $menuItems[] = ['label' => 'Log in', 'url' => ['/user/login']];
    } else {
        if (Yii::$app->user->can('Employee')) {
        $menuItems[] = ['label' => 'Employees', 'url' => ['/employee']];
        }
        $menuItems[] =['label' => 'About', 'url' => ['/site/about']];
        $menuItems[] =['label' => 'Contact us', 'url' => ['/site/contact']];
        if (Yii::$app->user->can('Employee')) {
            $dropDownItems[] = ['label' => 'My Projects', 'url' => '/employee/projects'];
            $dropDownItems[] = "<div class='dropdown-divider'></div>";
            $dropDownItems[] = ['label' => 'My Tasks', 'url' => '/employee/task-index'];
            $dropDownItems[] = "<div class='dropdown-divider'></div>";
        }

        $dropDownItems[] = ['label'=>'My Employee Profile', 'url'=>'/employee/my-profile'];
        $dropDownItems[] = "<div class='dropdown-divider'></div>";
        $dropDownItems[] = [
            'label'=>'Log Out (' . Yii::$app->user->identity->username . ')',
            'url'=>['user/logout'],
            'linkOptions'=>['data-method' => 'post'],
        ];
        $menuItems[] = [
            'label' => FAS::icon('user-circle')->size(FAS::SIZE_2X),
            'items' => $dropDownItems
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto align-items-center'],
        'items' => $menuItems,
        'encodeLabels'=>false
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
<!--        --><?//= Alert::widget() ?>
        <?= $content  ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
