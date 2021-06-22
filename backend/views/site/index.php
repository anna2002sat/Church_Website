    <?php

/* @var $this yii\web\View */

use rmrevin\yii\fontawesome\FAS;
use yii\bootstrap4\Carousel;

$this->title = 'Main';
?>
    <link rel="stylesheet" href="/frontend/web/css/main.css">
    <div class="container-lg mt-2 mb-3 justify-content-center text-white bg-primary pt-4 pb-1 ">
        <p style="font-family: v_ALoveOfThunder; font-size: x-large; text-align: center">
            "Lord Jesus, change me and the world around me will be changed!"
        </p>
    </div>
<div class="site-index">
    <?= Carousel::widget([
    'items' => [
    // the item contains only the image
        '<img src="/frontend/web/images/carousel/1.jpg" class="img-responsive w-100 center-block" alt="церква">',
        '<img src="/frontend/web/images/carousel/2.JPG" class="img-responsive w-100 center-block" alt="хрещення">',
        '<img src="/frontend/web/images/carousel/3.jpg" class="img-responsive w-100 center-block" alt="новий рік">'],
        'options' => ['class' => 'carousel slide ', 'data-interval' => '5500'],
    ]);
    ?>



    <!--GUIDE INFORMATION-->
    <div class="bg-light">
        <div class="mt-5 mb-5">
            <h2 class="title text-center mt-3">What are you searching?</h2>
            <div class=" row mb-5 row-cols-md-4">
                <div class="col-3 text-center m-0">
                    <a href="admin/site/about">
                        <i class=" fas fa-church icon" style="color: blue; "></i><br>
                        <p class="mt-2">Info about us</p>
                    </a>
                </div>

                <div class="col-3 text-center m-0">
                    <a href="https://www.youtube.com/channel/UCJXEbTgU6NbCd4x6Z94bsyw?app=desktop">
                        <i class="icon fab fa-youtube " style="color: red"></i></br>
                        <p class="mt-2">Our videos</p>
                    </a>
                </div>
                <div class="col-3 text-center m-0">
                    <a href="admin/project">
                        <i class="icon fas fa-history " style="color: blue"></i></br>
                        <p class="mt-2">Our projects</p>
                    </a>
                </div>
                <div class="col-3 text-center m-0">
                    <a href="about#faq">
                        <i class="fas fa-question icon" style="color: red"></i></br>
                        <p class="mt-2 ">FAQ</p>
                    </a>

                </div>

            </div>
        </div>
    </div>



    <!--GATHERING TIMES-->
    <div class="bg-light">
        <div class="container-lg ">
            <h2 class="  text-center title ">Week meetings</h2>
            <div class="  row row-cols-1 row-cols-md-3 g-4 align-self-center mb-5">
                <div class="col gatherings">
                    <h4>Wednesday</h4>
                    <hr/>
                    <p>
                        Prayer group for womens!
                        <br>
                        Time: 10:00 </p>

                </div>
                <div class="col gatherings">
                    <h4>Friday</h4>
                    <hr/>
                    <p >Youth meetings at church!<br>
                        Time: 19:00
                    </p>
                </div>
                <div class="col gatherings">
                    <h4>Sunday</h4>
                    <hr  />
                    <p> Sunday meetings ! <br>
                        Time: 10:00
                    </p>
                </div>
            </div>
        </div>
    </div>


</div>
