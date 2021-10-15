<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    if(isset($_SESSION['login_user']))
    {// 로그인 한 유저인 경우
        if($_SESSION['login_user']['auth_level'] === "admin")
        {// 관리자인 경우
            // echo "
            //     <script>
            //         alert('관리자 계정으로 접근할 수 없습니다.\\n관리자 계정에서 로그아웃 합니다.');
            //         location.reload();
            //     </script>
            // ";
    
            // session_destroy();
        }
        else if($_SESSION['login_user']['auth_level'] === "common")
        {// 유저인 경우
        }
    }
?>
<style>
    /* 공용 레이아웃 */
    .mobile {display:none;}
    .header {position:fixed; width:100%; top:0; align-items:center; display:flex; justify-content:center; background-color:#fff; z-index:1040;}
    .header .gnb-menu {width:100%;}
    /* .header .gnb-menu .header-container {display:flex; align-items:center; justify-content:space-between;} */

    /* .header .gnb-menu .header-container .logo a {font-size:1.4rem; font-weight:600;} */
    .header .mo-gnb-menu .hd-logo img {height:83px; width:auto;}

    .header .gnb-menu .header-container .hd-user {display:flex; align-items:center; justify-content:flex-end; margin:0 auto; width:960px; padding:5px 0;}
    .header .gnb-menu .header-container .hd-user .mypage {display:flex; align-items:center; margin-right:15px;}
    .header .gnb-menu .header-container .hd-user .mypage .menu-txt {font-size:1rem; color:#8f8f8f; margin-bottom:2.5px;}
    .header .gnb-menu .header-container .hd-user .user-btn {display:inline-flex; align-items:center; font-size:1rem; color:#8f8f8f;}
    .header .gnb-menu .header-container .hd-user .user-btn:last-child {margin-right:0;}
    .header .gnb-menu .header-container .hd-logo {text-align:center; border-top:1px solid #e5e5e5;}
    .header .gnb-menu .header-container .hd-logo a {display:inline-block; max-width:158px; padding:8px 0 7px;}
    .header .gnb-menu .header-container .hd-logo a img {display:inline-block; width:75%;}
    /* .header .gnb-menu .header-container .hd-menu {display:flex; align-items:center;} */
    /* .header .gnb-menu .header-container .hd-menu {position:absolute; left:50%; transform:translateX(-50%); top:27px;} */
    .header .gnb-menu .header-container .hd-menu {background:#2a62ff; width:100%;}
    .header .gnb-menu .header-container .hd-menu .menu-container {font-family:'YonseiB'; display:flex; justify-content:space-between; width:960px; margin:0 auto; align-items:center; padding:10px 0;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-left {margin:0 auto; width:100%; display:flex; align-items:center; justify-content:space-between;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right {display:flex; align-items:center;}
    /* .header .gnb-menu .header-container .hd-menu .menu-container .menu-right span:nth-of-type(2) a, .header .gnb-menu .header-container .hd-menu .menu-container .menu-right span:nth-of-type(3) a {font-size:1.143rem; font-weight:400;} */
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right span a {font-family:'YonseiB'; font-size:1.143rem;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-txt {font-family:'YonseiB'; padding:0 20px; text-align:center;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-txt:first-child {padding-left:0;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-txt:last-child {padding:0;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-txt a {font-family:'YonseiB'; position:relative; font-size:1.143rem; color:#ffe856; line-height:20px;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-txt a:after {content:''; position:absolute; bottom:-3px; left:0; width:0; height:1px; background-color:#fff;} 
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-txt a:hover:after {width:100%; transition:.3s;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right a {color:#ffe856; position:relative;}
    /* .header .gnb-menu .header-container .hd-menu .menu-container .menu-right .user-level::after {position:absolute; top:55%; right:0; transform:translateY(-50%); content:''; background:rgba(255, 255, 255, 0.2); width:2px; height:15px;} */
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right .menu-txt {padding-left:8px;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right .user-level {position:relative; padding-right:8px; display:flex; align-items:center;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right .user-level a {background:#5783ff; padding:3px 7px; font-size:.9rem; border-radius:20px;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right .user-name a {background:none; font-size:1.143rem; border-radius:0; margin-right:0; padding:0;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right .user-name a strong {vertical-align:baseline; font-weight:500;}
    .header .gnb-menu .header-container .hd-menu .menu-container .menu-right .user-name:after {position:absolute; top:55%; right:0; transform:translateY(-50%); content:''; background:rgba(255, 255, 255, 0.2); width:2px; height:15px;} 
    


    /* .hd-search {display:flex; align-items:center;}
    .hd-search .hd-search-input {display:none; position:relative; margin-right:5px;}
    .hd-search .hd-search-input input[type='text'] {padding:8px 35px 8px 15px; border-radius:30px; border:1px solid #d6d6d6; font-size:.9rem; width:15vw;}
    .hd-search .hd-search-input input[type='text']::placeholder {font-size:.8rem;}
    .hd-search .hd-search-input .hd-search-submit {position:absolute; top:50%; right:6px; transform:translateY(-50%); display:flex; align-items:center; justify-content:center; border-radius:50%; background-color:#ffb518; padding:6px;}
    .hd-search .hd-search-input .hd-search-submit i {color:#fff; font-size:.9rem;}
    .hd-search .hd-search-input.active {display:inline-block;}
    .hd-search .hd-search-input::placeholder {font-size:.8rem;}
    .hd-search .hd-search-btn {display:flex; align-items:center;}
    .hd-search .hd-search-btn i {font-size:1.3rem; cursor:pointer;} */
    
    @media screen and (max-width: 768px) {
        .mobile {display:block;}
        .move-cont {transform:translate(-280px, 0);}
        .body-cont {transition:.3s;}
        .mo-side-cover {z-index:105; display:none; position:fixed; top:0; right:0; bottom:0; left:0; background-color:rgba(0,0,0,0.4);}
        .header nav > ul > li > a {color:#000;}
        .header nav > ul > li > a:hover:before {display:none;}

        .hd-search {padding:0 10px; width:100%; margin-bottom:15px;}
        .hd-search .hd-search-input.active {width:100%;}
        .hd-search .hd-search-input input[type='text'] {width:100%;}
    
        .header .mo-gnb-menu {width:100%; padding:15px; display:flex!important; align-items:center; justify-content:space-between; background-color:#fff;}
        .header .mo-gnb-menu .hd-logo a {display:inline-block; width:100%;height:100%;}
        .header .mo-gnb-menu .hd-logo img {width:auto; height:24px;}


        .header { position:fixed; width:100%;}
        .header .inner {padding:0 20px;}
        .header .inner:after {}
        .header h1 {}
        .header h1 a {}
        .header nav.hd-pc-nav {display:none;}
        .header nav.hd-mo-nav {display:block; width:100%;}
        .header nav.hd-mo-nav ul {width:100%;}
        
        .header .toggle_open {position:relative; display:inline-block; float:right; vertical-align:top; width:20px; background:transparent; transition:all 0.5s; z-index:1;}
        .header .toggle_open .line {position:absolute; width:20px; height:18px; top:50%; left:50%; transform: translate(-50%,-50%);}
        .header .toggle_open .line span {position:absolute; width:100%; height:3px; right:0; background:#171717; transition:opacity 0.3s, top 0.5s, transform 0.5s; transition-delay:0s, 0.3s, 0s;}
        .header .toggle_open .line span.top {top:0; transform:rotate(0);}
        .header .toggle_open .line span.mid {top:7.5px; opacity:1;}
        .header .toggle_open .line span.btm {top:15px; transform:rotate(0);}
        .header.toggle_active .toggle_open .line span {transition:opacity 0.3s, top 0.3s, transform 0.5s; transition-delay:0s, 0s, 0.3s;}
        .header.toggle_active .toggle_open .line span.top {top:7.5px; transform:rotate(-45deg)}
        .header.toggle_active .toggle_open .line span.mid {opacity:0;}
        .header.toggle_active .toggle_open .line span.btm {top:7.5px; transform:rotate(45deg)}


        .mo-sidebar {display:block; z-index:110; position:fixed; top:0; width:80%; max-width:280px; height:100%; background-color:#fff; transition:.3s; right:0; transform:translate(100%, 0);}
        .mo-sidebar.active {transform:translate(0, 0);}
        .mo-sidebar .sidebar-close {padding:15px 20px; display:flex; justify-content:flex-end; align-items:center;}
        .mo-sidebar .sidebar-close button i {font-size:1.5rem; font-weight:600;}
        .mo-sidebar ul.sidebar-menu {padding:10px 20px;}
        .mo-sidebar ul.sidebar-menu li {display:flex; align-items:center; justify-content:space-between; margin-bottom:15px;}
        .mo-sidebar ul.sidebar-menu li a {width:100%; height:100%; font-size:1.2rem; font-weight:600;display: inline-flex;align-items: center;justify-content: space-between;}
        .mo-sidebar ul.sidebar-menu li a i {font-size:1.2rem;}
        .mo-sidebar ul.sidebar-menu li.mo-menu-depth {display:block;}
        .mo-sidebar ul.sidebar-menu li.mo-menu-depth span {width:100%; height:100%; font-size:1.2rem; font-weight:600;display: inline-flex;align-items: center;justify-content: space-between; cursor:pointer;}
        .mo-sidebar ul.sidebar-menu li.mo-menu-depth .mo-menu-2depth {display:none;}
        .mo-sidebar ul.sidebar-menu li.mo-menu-depth .mo-menu-2depth.active {display:block; margin-top:8px;}}
        .mo-sidebar ul.sidebar-menu li.mo-menu-depth .mo-menu-2depth a {font-size:1.1rem; font-weight:normal; padding-left:10px; line-height:1.8;}
        .mo-sidebar .sidebar-link {position:absolute; bottom:50px; left:50%; display:inline-flex; align-items:center; margin-left:6px; padding:0 5px; border:1px solid #225aa8; border-radius:30px; transform:translate(-50%, 0);}
        .mo-sidebar .sidebar-link a {position:relative; padding:6px 10px; font-size:.9rem; color:#225aa8;}
        .mo-sidebar .sidebar-link a:first-child:after {content:''; position:absolute; right:0; top:50%; width:1px; height:40%; background-color:#225aa8; transform:translate(0, -50%);}
        .mo-sidebar .sidebar-link .mo-link-youtube.active:after {content:'준비 중입니다.'; position:absolute; bottom:130%; left:50%; width:80px; padding:10px; border: 1px solid #ddd; text-align:center; background-color:#fff; border-radius:6px; transform:translate(-50%, 0); font-size:.8rem; font-weight:600; color:#444;}
        .mo-sidebar .mo-side-bot {position:absolute; bottom:0; left:0; width:100%; padding:20px 15px 0; margin-bottom:30px; display:flex; align-items:flex-start; flex-direction:column; border-top:1px solid #d6d6d6;}
        .mo-sidebar .mo-side-bot .mo-side-user {display:flex; align-items:center; justify-content:center; margin-bottom:20px;}
        .mo-sidebar .mo-side-bot .mo-side-user .mo-user-btn {margin-right:14px;}
        .mo-sidebar .mo-side-bot .mo-side-user .mo-user-btn:last-child {margin-right:0;}
        .mo-sidebar .mo-side-bot .mo-side-user .mo-user-btn a {font-size:1.1rem; color:#565656; font-weight:600;}
        .mo-sidebar .mo-side-bot .mo-side-sns {}
        .mo-sidebar .mo-side-bot .mo-side-sns a {display:inline-flex; align-items:center; justify-content:center; width:35px; height:35px; background-color:#d6d6d6; border-radius:50%; margin-right:2px;}
        .mo-sidebar .mo-side-bot .mo-side-sns a i {font-size:1.2rem;}
        .mo-sidebar .mo-side-bot .mo-side-sns a:last-child {margin-right:0;}
    }

</style>
<header>
    <div class='header'>
        <!-- GNB 메뉴 -->
        <div class='pc gnb-menu'>
            <!-- 로고 -->
            <div class='header-container'>
                <div class='hd-user'>
                    <div class='mypage'>
                        <?php                        
                            if(isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "common")
                            {    //로그인 O - 유저
                                if($_SESSION['login_user']['rank'] === "sales")
                                {// 영업사원인 경우
                                    echo "
                                        <span class='user-level'>
                                            <a href='#'>영업사원</a>
                                        </span>
                                    ";
                                }
                                echo "
                                    <span class='user-level user-name'>
                                        <a href='#'>{$_SESSION['login_user']['name']}님</a>
                                    </span>
                                    <span class='menu-txt'>
                                        <a href='{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/mypage/myinfo.php'>마이페이지</a>
                                    </span>
                                ";                        
                            }                        
                            else if(isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "admin")
                            {    //로그인 O - 관리자
                                
                                echo "
                                    <span class='menu-txt'>
                                        <a href='{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/mypage/myinfo.php'>마이페이지</a>
                                    </span>
                                ";                        
                            }
                            else
                            {//로그인 X
                                echo "
                                    <!--<span class='menu-txt'>
                                        <a href='{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/mypage/myinfo.php'>마이페이지</a>
                                    </span>-->
                                ";
                            } 
                        ?>  
                    </div>
                    <?php                        
                        if(isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "common")
                        {    //로그인 O - 유저
                            
                            echo "
                                <span class='logout user-btn'>
                                    <a href='#'>로그아웃</a>
                                </span>
                            ";                        
                        }                        
                        else if(isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "admin")
                        {    //로그인 O - 관리자
                            
                            echo "
                                <span class='logout user-btn'>
                                    <a href='#'>로그아웃</a>
                                </span>
                            ";                        
                        }
                        else
                        {//로그인 X
                            echo "
                                <span class='user-btn'>
                                    <a href='{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/login.php'>로그인/</a>
                                </span>
                                <span class='user-btn'>
                                    <a href='{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/join.php'>회원가입</a>
                                </span>
                            ";
                        } 
                    ?>
                    <!-- <div class='hd-search'>
                        <form class='hd-search-input active' action='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/movie/search_result.php'>
                            <input type='text' name='search_keyword' placeholder='검색어를 입력하세요.' value="<?= isset($_REQUEST["search_keyword"]) ? $_REQUEST["search_keyword"] : "" ?>">
                            <button class='hd-search-submit'><i class='xi-search'></i></button>
                        </form>
                        <span class='hd-search-btn'><i class='xi-search'></i></span>
                    </div> -->
                </div>
                <div class='hd-logo'>
                    <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/index.php'><img src='<?=$PATH['RESOURCES']?>/image/siljeok/siljeoklogo.png' alt='siljeok'></a> 
                </div>
                <div class='hd-menu'>
                    <div class='menu-container'>  
                        <div class='menu-left'>
                            <span class='menu-txt'>
                                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/board/common/list.php'>실적스토어게시판</a>
                            </span>
                            <span class='menu-txt'>
                                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/board/notice/list.php'>자유게시판</a>
                            </span>
                            <span class='menu-txt'>
                                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/board/profile/list.php'>프로필게시판</a>
                            </span>
                            <span class='menu-txt'>
                                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/findUser/list.php'>영업사원 찾기</a>
                            </span>
                            <span class='menu-txt'>
                                <a href='https://www.pay-back-korea.com/' target='_blank'>실시간 리스.렌트견적<br>PAY-BACK KOREA</a>
                            </span>
                        </div>
                        <!-- <div class='menu-right'></div> -->
                    </div>
                    
                </div>
                
            </div>
        </div>
        <div class='mo-gnb-menu mobile'>
            <div class='hd-logo'>
                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/index.php'><img src='<?=$PATH['RESOURCES']?>/image/siljeok/siljeoklogo.png' alt='siljeok'></a> 
            </div>
            <a class="toggle_open">
                <span class="line">
                    <span class="top"></span>
                    <span class="mid"></span>
                    <span class="btm"></span>
                </span>
            </a>
        </div>
        <div class='mobile'>
            <!-- mobile -->
            <div class='mo-side-cover'></div>
            <sidebar class='mo-sidebar'>
                <p class='sidebar-close'><button><i class='xi-close'></i></button></p>
                <!-- <div class='hd-search'>
                    <form class='hd-search-input active' action='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/movie/search_result.php'>
                        <input type='text' name='search_keyword' placeholder='영화제목, 감독, 키워드를 검색해보세요.' value="<?= isset($_REQUEST["search_keyword"]) ? $_REQUEST["search_keyword"] : "" ?>">
                        <button class='hd-search-submit'><i class='xi-search'></i></button>
                    </form>
                    <span class='hd-search-btn'><i class='xi-search'></i></span>
                </div> -->
                <nav class='hd-mo-nav'>
                    <ul class='sidebar-menu'>
                        <li><a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/board/common/list.php'>실적스토어게시판</a></li>
                        <li><a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/board/notice/list.php'>자유게시판</a></li>
                        <li><a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/board/profile/list.php'>프로필게시판</a></li>
                        <li><a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/board/common/list.php'>영업사원 찾기</a></li>
                        <li><a href='https://www.pay-back-korea.com/' target='_blank'>실시간 리스.렌트견적<br>PAY-BACK KOREA</a></li>
                        <li class='mo-menu-depth'>
                            <span class='mo-menu-1depth'>마이페이지</span>
                            <p class='mo-menu-2depth'>
                                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/mypage/myinfo.php'>회원정보수정</a>
                                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/mypage/password_modify.php'>비밀번호 변경</a>
                                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/order/list.php'>구매내역</a>
                                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/consult/list.php'>1:1 문의 내역</a>
                            </p>
                        </li>
                    </ul>
                </nav>
                <div class='mo-side-bot'>
                    <div class='mo-side-user'>
                        <?php                        
                            if(isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "common")
                            {    //로그인 O
                                
                                echo "
                                    <span class='logout mo-user-btn'>
                                        <a href='#'>로그아웃</a>
                                    </span>
                                ";                        
                            }                        
                            else if(isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "admin")
                            {    //로그인 O
                                
                                echo "
                                    <span class='logout mo-user-btn'>
                                        <a href='#'>로그아웃</a>
                                    </span>
                                ";                        
                            }
                            else
                            {//로그인 X
                                echo "   
                                    <span class='mo-user-btn'>
                                        <a href='{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/login.php'>로그인</a>
                                    </span>
                                    <span class='mo-user-btn'>
                                        <a href='{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/join_intro.php'>회원가입</a>
                                    </span>
                                ";
                            } 
                        ?>
                    </div>
                    <div class='mo-side-sns'>
                        <a href='https://www.facebook.com/ONFIFNofficial'><i class='xi-facebook'></i></a>
                        <a href='https://twitter.com/ONFIFN_twt'><i class='xi-twitter'></i></a>
                        <a href='https://www.instagram.com/onfifn/'><i class='xi-instagram'></i></a>
                        <a href='https://www.youtube.com/channel/UC6OROy596WTltDVKdTlVQhw'><i class='xi-youtube'></i></a>
                    </div>
                </div>
            </sidebar>
        </div>

    </div>
</header>



<!-- 구글번역기 영역 -->
<div id="google_translate_element"></div>  
<style>
    /* 구글 번역 탑바 숨기기 */
    body{top:0 !important;}
    .goog-te-banner-frame.skiptranslate {display: none !important;} 
    #google_translate_element{display:none !important;}
</style>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


<script>
    var g_login_level_type = 'common';  
    var g_is_auto_login = localStorage.getItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>auto_login") === "true" ? true : false;

    var login_type = g_login_level_type.substring(0,1).toUpperCase() + g_login_level_type.substring(1);
    var g_page_init_complete = false;

    $(function(){
        $('.logout').on('click', logout);

        $('#g_search_product_name').on('submit', function(e){
            var keyword = $('#g_search_product_name_value').val();

            location.href = "<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/product/list.php?name=" + keyword;
        });

        $('.category').hover(function () 
        {
            if($("#category-box").css("display") == "none"){   
                jQuery('#category-box').css("display", "block");   
            } else {  
                jQuery('#category-box').css("display", "none");   
            }  
        });

        // 자동 로그인
        if(g_is_auto_login && <?=isset($_SESSION['login_user']) ? 'false' : 'true'?>){
            $('input[name=saved_auto_login_check]').prop("checked", true);
            login("auto");
        }

        $('.category2').hover(function () {  
            if($("#category-box2").css("display") == "none"){   
                $('#category-box2').css("display", "block");   
            } else {  
                $('#category-box2').css("display", "none");   
            }  
        });


        // 21.07.20 검색창 열기
        $('.hd-search-btn').on('click', clickHeaderSearch);
        // 21.07.23 모바일 depth 펼치기
        $('.mo-menu-1depth').on('click', clickMobileDepth);

        
        //모바일 메뉴 사이드바 클릭
        $('.toggle_open').on('click',moSideBar);
        // 모바일 메뉴 사이드바 닫기
        $('.sidebar-close button').on('click',moSideClose);
        $('.mo-side-cover').on('click',moSideClose);
        

    });


    //모바일 메뉴 사이드바 클릭
    function moSideBar(){
        $('.mo-sidebar').addClass('active');
        $('.body-cont').addClass('move-cont');
        $('.gnb-mo').addClass('move-cont');
        $('.mo_side-menu').css('display','none');
        $('.mo-side-cover').css('display','block');
    }

    // 모바일 메뉴 사이드바 닫기
    function moSideClose(){
        $('.mo-sidebar').removeClass('active');
        $('.body-cont').removeClass('move-cont');
        $('.gnb-mo').removeClass('move-cont');
        $('.mo_side-menu').css('display','block');
        $('.mo-side-cover').css('display','none');
    }

    // 21.07.23 모바일 depth 펼치기
    function clickMobileDepth(e){
        var target = $(e.currentTarget);

        target.siblings('.mo-menu-2depth').toggleClass('active');
    }


    // 로그인 (2020.05.27 By.Chungwon)
    function login(type){
        var params = autoGetItem();

        if(g_is_auto_login && type ==='auto'){

            params.append('id', localStorage.getItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>id"));
            params.append('password', localStorage.getItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>pw"));
        }
        var plist = getFormData(params);

        if(empty(plist['id']) || empty(plist['password'])){
            alert("로그인 정보를 입력해주세요.");
            return;
        }

        // 로그인 실행
        sendAPI("/member", "login", params, function(res){
            if(res.login_status){
                // 로그인 성공
                
                // 자동로그인 세션 저장
                var saved_auto_login_check = $('input[name=saved_auto_login_check]').prop("checked");

                if(saved_auto_login_check){
                    // 자동 로그인이 눌렸다면, 아이디, 패스워드를 로컬 세션에 저장
                    localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>id", plist['id']);
                    localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>pw", plist['password']);
                    localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>auto_login", "true");

                }else{
                    // 자동 로그인이 눌리지 않았다면, 패스워드를 로컬 세션에서 삭제
                    localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>id", "");
                    localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>pw", "");
                    localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>auto_login", "false");

                    // 만약 아이디 로그인이 필요한 경우, 자동로그인 로직 변경 (아이디는 저장하도록)
                }

                // var ref = document.referrer.split('/')[2];
                // var domain = ref.substring(ref.indexOf('.')+1, ref.length);
                // var target_domain = "<?=$SITE['DOMAIN']?>";

                // if (domain.indexOf(target_domain) === -1)
                //     window.location.replace(document.referrer);
                // else if (ref.indexOf(target_domain) === -1)
                //     window.location.replace(document.referrer);
                // else
                //     window.location.replace("<?=$SITE['LINK']?>");
                location.href = "<?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>"; // 플레이어
            }
        }, null);

        
    }
    // 로그아웃
	function logout(){
        sendAPI("/member", "logout", {}, function(res){
            // 자동 로그인 초기화
            localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>id", "");
            localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>pw", "");
            localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>auto_id", false);
            localStorage.setItem("<?=$SITE['E_NAME']?>_<?=$PREFIX['COMMON']?>auto_login", false);

            location.href = "<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/index.php";
        }, null);
    }


    // 21.07.20 검색창 열기
    function clickHeaderSearch(e){
        var target = $(e.currentTarget);
        target.find('i').toggleClass('xi-close');
        target.siblings('.hd-search-input').toggleClass('active');
    }

</script>