<?php
    require_once 'lib/db.php';
    $sql = "SELECT * FROM `board`";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
    <link rel="stylesheet" href="css/jquery.bxslider.css">
    <link rel="stylesheet" type="text/css" href="css/style.css?ver=1">
    <script src="js/jquery-3.6.0.js"></script>
    <script src="js/jquery.bxslider.js"></script>
    <title>Document</title>
    <script>

        $(document).ready(function(){
            var a = $('.slider').bxSlider({

            auto: true, 
            speed: 500, 
            pause: 1000, 
            mode:'fade', 
            //'fade', 'horizontal', 'vertical' 기본 값 horizontal//
            autoControls: true, 
            pager:true,
            captions: true,
            slideWidth: 800,

        });
        
            $('.control .start').on('click', function(){
                a.startAuto();
            })

            $('.control .pause').on('click', function(){
                a.stopAuto();
            })
        });
        

    </script>
</head>

<body>
    <header>

        <div class="logo"><a href="index.php"><img src="img/Logo20190813203959.png"></a></div>

        <nav>
            <ul class="main-menu">
                <li><a href="">메인메뉴</a>
                    <ul class="sub-menu">
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                    </ul>
                </li>

                <li><a href="">메인메뉴</a>
                    <ul class="sub-menu">
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                    </ul>
                </li>

                <li><a href="">메인메뉴</a>
                    <ul class="sub-menu">
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                    </ul>
                </li>

                <li><a href="">메인메뉴</a>
                    <ul class="sub-menu">
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                        <li><a href="">서브메뉴</a></li>
                    </ul>
                </li>

                <li><a href="">게시판</a>
                    <ul class="sub-menu">
                        <li><a href="view/board.php">자유게시판</a></li>
                        <li><a href="">공지사항</a></li>
                        <li><a href="">서브메뉴</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="slider">
            <div><img src="img/LakeMorain.jpg" title="캡션1"></div>
            <div><img src="img/RedSky.jpg" title="캡션2"></div>
            <div><img src="img/Road.jpg" title="캡션3"></div>
            <div><img src="img/Wallpaper_(33).jpg" title="캡션4"></div>
            <div><img src="img/Wallpaper_(6).jpg" title="캡션5"></div>
        </div>

    </header>

    <article>

        <div class="tabs">
            <ul>
                <li class="work01">work 01</li>
                <li class="work02">work 02</li>
                <li class="work03">work 03</li>
                <li class="work04">work 04</li>
                <li class="work05">work 05</li>
            </ul>
        </div>

        <div class="contents">
            <div class="panel" id="work01"></div>
            <div class="panel" id="work02"></div>
            <div class="panel" id="work03"></div>
            <div class="panel" id="work04"></div>
            <div class="panel" id="work05"></div>
        </div>
        
    </article>
    <script src="js/main.js?ver=1"></script>
</body>

</html>