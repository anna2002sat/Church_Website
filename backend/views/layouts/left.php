<?php

use frontend\models\Employee;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Employee::findOne(['user_id'=>Yii::$app->user->getId()])->getImage()?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->username ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                    [
                        'label' => 'Employees',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                                ['label' => 'Manage employees', 'icon' => 'file-code-o', 'url' => ['/employee'],],
                                [ 'label' => 'Create Employee', 'icon' => 'file-code-o', 'url' => ['/employee/create'],],
                        ]
                    ],
                    [
                        'label' => 'Managers',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Edit manager list', 'icon' => 'file-code-o', 'url' => ['/employee/managers'],],
                        ]
                    ],
                    [
                        'label' => 'Projects',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Manage Projects', 'icon' => 'file-code-o', 'url' => ['/project'],],
                            ['label' => 'Create Project', 'icon' => 'file-code-o', 'url' => ['/project/create', 'isMyProjects'=>false],],
                            ['label' => 'My Projects', 'icon' => 'file-code-o', 'url' => ['/project', 'isMyProjects'=>true],],
                        ]
                    ],
                    [
                        'label' => 'Tasks',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Список категорій', 'icon' => 'file-code-o', 'url' => ['/category/index'],],
                            ['label' => 'Нова категорія', 'icon' => 'file-code-o', 'url' => ['/category/create'],],
                        ]
                    ],
                    [
                        'label' => 'Клієнти',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Список клієнтів', 'icon' => 'file-code-o', 'url' => ['/client/index'],],
                            ['label' => 'Новий клієнт', 'icon' => 'file-code-o', 'url' => ['/client/create'],],
                        ]
                    ],

                    [
                        'label' => 'Статистика',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Кількість сеансів', 'icon' => 'file-code-o', 'url' => ['/excursion/booking-period'],],
                            ['label' => 'Кількість бронювань', 'icon' => 'file-code-o', 'url' => ['/booking/statistic'],],
                            ['label' => 'Рейтинг', 'icon' => 'file-code-o', 'url' => ['/excursion/popular'],],
                        ]
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
