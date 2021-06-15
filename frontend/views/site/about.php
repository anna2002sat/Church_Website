<?php

use yii\bootstrap4\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;?>

<link rel="stylesheet" href="/frontend/web/css/about.css">

<!-- history -->
<div>
    <div class="container">
        <div class="row mb-5">
            <div class="col-sm-7">
                <div class="card border-0 m-3">
                    <div class="card-body p-0 m-0">
                        <img alt="Church" src="/frontend/web/images/church.JPG" class="img-fluid rounded-end m-0" >
                    </div>
                </div>
            </div>
            <div class="col-sm-5 align-self-center">
                <div class="card border-0 ">
                    <div class="card-body">
                        <h5 class="title card-title text-center ">History</h5>
                        <p class="card-text text-justify">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid dignissimos dolor facere fugit ipsam laudantium magni nesciunt nihil nostrum numquam placeat quo rem, sunt tenetur unde, vel vero.
                            <br> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid dignissimos dolor facere fugit ipsam laudantium magni nesciunt nihil nobis nostrum numquam placeat quo rem, sunt tenetur unde, vel.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--FAQS-->
<div style="background-color: gainsboro; border-radius: 4px" id="faq">
    <div class="container-lg">
        <div class="container-lg pb-5 ">
            <h4 class=" title mb-3 text-center mt-lg-4 p-3 text-black" >Frequently Asked Questions</h4>
            <div class="row g-2 ">
                <div class="col-sm-6" >
                    <button type="button" class="collapsible">Where is your nonprofitable organization located?</button>
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
                <div class="col-sm-6 " >
                    <button type="button" class="collapsible">How you can help me and my family?</button>
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
                <div class="col-sm-6 " >
                    <button type="button" class="collapsible">What is your denomination?</button>
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
                <div class="col-sm-6" >
                    <button type="button" class="collapsible">How do you influence comunity? </button>
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
                <div class="col-sm-6" >
                    <button type="button" class="collapsible">What goals you want to achieve?</button>
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
                <div class="col-sm-6 " >
                    <button type="button" class="collapsible">How can I join your organization?</button>
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
                <div class="col-sm-6 " >
                    <button type="button" class="collapsible">What is the official name of your organization?</button>
                    <div class="content">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </div>
                </div>
                <div class="col-sm-6" >
                    <button type="button" class="collapsible">How can I connect your the administrator?</button>
                    <div class="content">
                        <p>You can click the link bellow to write an mail to the administrator if you have any problems or questions!</p>
                        <p> <?= Html::a('Click this link', ['contact']) ?></p>
                        <p>Thanks, for being with us ;)</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Our Staff -->
<h2 class="container text-center title">Founders</h2>
<div class="staff container row row-cols-1 row-cols-md-4 g-4 align-self-center mb-5">
    <div class="col">
        <div class="card cardShadow h-100">
            <img src="/frontend/web/images/staff/pastorTim.jpg" class="card-img-top" alt="..." style="max-height: 400px;">
            <div class="card-body">
                <h5 class="card-title">Tymothy Strelchenko</h5>
                <p class="text-muted">Pastor</p>
                <p class="card-text"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque eaque nemo nisi quisquam reprehenderit sequi vitae voluptatem voluptates?</p>
                <p class="card-text">tvstrelchenko@gmail.com</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card cardShadow h-100">
            <img src="/frontend/web/images/staff/deacon.jpg" class="card-img-top" alt="..." style="max-height: 400px;">
            <div class="card-body">
                <h5 class="card-title">Tymothy Savenko</h5>
                <p class="text-muted">Deakon</p>
                <p class="card-text"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque eaque nemo nisi quisquam reprehenderit sequi vitae voluptatem voluptates?</p>
                <p class="card-text">savenkoTim@gmail.com</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card cardShadow h-100">
            <img src="/frontend/web/images/staff/teacher.jpg" class="card-img-top" alt="..." style="max-height: 400px;">
            <div class="card-body">
                <h5 class="card-title">Anna Strelchenko</h5>
                <p class="text-muted">Sunday School Teacher</p>
                <p class="card-text"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque eaque nemo nisi quisquam reprehenderit sequi vitae voluptatem voluptates?</p>
                <p class="card-text">anna@gmail.com</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card cardShadow h-100">
            <img src="/frontend/web/images/staff/vicePastor.jpg" class="card-img-top" alt="..." style="max-height: 400px;">
            <div class="card-body">
                <h5 class="card-title">John Strelchenko</h5>
                <p class="text-muted">Vice-Pastor</p>
                <p class="card-text"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque eaque nemo nisi quisquam reprehenderit sequi vitae voluptatem voluptates?</p>
                <p class="card-text">john@gmail.com</p>
            </div>
        </div>
    </div>
</div>


<!--FAITH-->
<hr class="container " style="border: #0dcaf0 2px solid; opacity: 1; background-color: #0a53be;" >

<h2 class="title container text-center mt-2 mb-3">Our basic principles of faith</h2>

<div class="jumbotron pt-0 jumbotron-fluid container">
    <div class="row ">
        <div class="col-sm-5 ">
            <ul class="list-unstyled ml-4 text-left">
                <li class="media">
                    <div class="media-body">
                        <h5 class="mt-0"><i class="fas fa-church" style="font-size: 50px; color: black" ></i> Sola scriptura ("by Scripture alone")</h5>
                        <p>Bible can and is to be interpreted through itself, with one area of Scripture being useful for interpreting others.</p>
                    </div>
                </li>
                <li class="media ">
                    <div class="media-body">
                        <h5 class="mt-0"><i class="fas fa-church" style="font-size: 50px; color: red" ></i> Sola fide ("by faith alone")</h5>
                        <p>Justification is received by faith alone, without any need for good works on the part of the individual.</p>
                    </div>
                </li>
                <li class="media">
                    <div class="media-body">
                        <h5 class="mt-0"><i class="fas fa-church" style="font-size: 50px; color: blue" ></i>  Solo Christo ("Christ alone")</h5>
                        <p>Christ is the only mediator between God and man.</p>
                    </div>
                </li>
                <li class="media">
                    <div class="media-body">
                        <h5 class="mt-0"><i class="fas fa-church " style="font-size: 50px; color:#94e061;" ></i> Sola gratia ("by grace alone")</h5>
                        <p>Salvation is an unearned gift from God for Jesus's sake.</p>
                    </div>
                </li>
                <li class="media">
                    <div class="media-body">
                        <h5 class="mt-0"><i class="fas fa-church " style="font-size: 50px; color:gold;" ></i> Soli Deo gloria ("glory to God alone")</h5>
                        <p> all glory is to be due to God alone, since salvation is accomplished solely through his will and action</p>
                    </div>
                </li>

            </ul>
            <ul class="list-unstyled list-inline circles ml-lg-5 justify-content-sm-center ">
                <li class="m-1 shadow" style="background-color: black; "></li>
                <li class="m-1 shadow" style="background-color: red;"></li>
                <li class="m-1 shadow" style="background-color: blue"></li>
                <li class="m-1 shadow" style="background-color: #94e061"></li>
                <li class="m-1 shadow" style="background-color: gold"></li>
            </ul>
        </div>
        <div class="col-sm-7 float-right">
            <img src="/frontend/web/images/faith.png" height="630" width="755" class="img-responsive float-right">
        </div>
    </div>
</div>



<!--CONTACT INFORMATION-->
<div class="bg-light">
    <div class="container mt-5 mb-5">
        <h2 class="title text-center mt-3">Social Media</h2>
        <div class=" row mb-5 row-cols-md-4">
            <div class="col-3 text-center m-0">
                <a href="https://m.youtube.com/channel/UCJXEbTgU6NbCd4x6Z94bsyw">
                    <i class="icon fab fa-youtube " style="color: red"></i></br>
                    <p class="mt-2">Youtube</p>
                </a>
            </div>
            <div class="col-3 text-center m-0">
                <a href="https://msng.link/o/?380971449968=vi">
                    <i class="fab fa-viber icon" style="color: #7557f3"></i></br>
                    <p class="mt-2">Pastor`s Viber</p>
                </a>
            </div>
            <div class="col-3 text-center m-0">
                <a href="https://instagram.com/molodizhka_za_zirkoy?igshid=tnl4lwszzayw">
                <span class="instagram ">
                <i class=" fab fa-instagram icon" style="font-size: 60px;color: white "></i><br>
                    </span>

                    <p class="mt-2">Instagram</p>
                </a>
            </div>
            <div class="col-3 text-center m-0">
                <a href="mailto:tserkvazazirkoy@gmail.com">
                    <i class="far fa-envelope icon" style="color: blue"></i></br>
                    <p class="mt-2">Gmail</p>
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;
    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("activeQ");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            }
            else {
                content.style.display = "block";
            }
        });
    }
</script>