
<style>
    /* 공용 레이아웃 */
    footer {width: 100%;/*  background-color: #fff;  position:absolute;*/ bottom:0; left:0; }
    .wrap {padding: 0 !important;}
    /* .container {max-width: 1400px; margin: 0 auto;} */

    /* 스크롤탑 */
    a.scroll-top {position: fixed; right:42px; bottom: 50px; display: none; z-index:999;}
    a.scroll-top img {width:50px; height:auto;}

    /* 푸터 */
    .footer {}
    .footer .ft-cont {}
    .footer .ft-section01 {background-color:#fff; padding:20px 0; border-top:1px solid #c6c6c6;border-bottom:1px solid #c6c6c6;}
    .footer .ft-section01 .ft-sec2-top {display:flex; align-items:center; width:960px; margin:0 auto; }
    .footer .ft-section01 .ft-sec2-top .ft-terms {}
    .footer .ft-section01 .ft-sec2-top .ft-terms a {color:#0b0b0b; line-height:2; font-size:1rem; font-weight:400;}
    .footer .ft-section01 .ft-sec2-top .ft-terms .ft-terms-item {display:inline-block; position:relative; margin-right:25px; line-height:1.5; font-family:'YonseiB';}
    .footer .ft-section01 .ft-sec2-top .ft-terms .ft-terms-item:last-child {margin-right:0;/*  font-weight:700; */}
/*     .footer .ft-section01 .ft-sec2-top .ft-terms .ft-terms-item:after {content:''; position:absolute; top:50%; right:-15px; transform:translateY(-50%); width:1px; height:60%; background-color:#000;} */
    .footer .ft-section01 .ft-sec2-top .ft-terms .ft-terms-item:last-child:after {display:none;}
    
    .footer .ft-section02 {padding:35px 0 40px; display:flex; width:960px; margin:0 auto; justify-content:space-between;}
    .footer .ft-section02 .ft-cont {}
    .footer .ft-section02 .ft-line span {display:block; color:#000; font-size:1rem; font-family:'YonseiB'; font-weight:400;}
    .footer .ft-section02 .ft-line .ft-copyright {color:#8f8f8f; font-size:1rem; margin-top:8px;}
    .footer .ft-section02 .ft-cominfo { }
    .footer .ft-section02 .ft-cominfo .ft-line {}
    .footer .ft-section02 .ft-cominfo .ft-line span {display:inline-block; position:relative; margin-right:25px; color:#000; line-height:25px;}
    .footer .ft-section02 .ft-cominfo .ft-line span:last-child {margin-right:0;}
    .footer .ft-section02 .ft-cominfo .ft-line span:after {content:''; position:absolute; top:50%; right:-12px; transform:translateY(-50%); width:1px; height:60%; background-color:#000;}
    .footer .ft-section02 .ft-cominfo .ft-line span:last-child:after {display:none;}
    .footer .ft-section02 .ftc-right {display:flex; flex-direction:column; justify-content:space-between; text-align:right; align-items:flex-end;}
    .footer .ft-section02 .ftc-right .ft-tel {margin-top:8px; font-size:1.714rem;}
    .footer .ft-section02 .ft-logo {width:102px; height:auto;}
    .footer .ft-section02 .ft-logo img {display:inline-block; width:100%;}
</style>

<!-- 스크롤탑 -->
<a href="#" class="scroll-top"><img src="<?=$PATH['RESOURCES']?>/image/icon/btn_pagetop.png" alt=""></a>

<footer>
    <div class='footer'>

        <div class='ft-section01'>
            <div class='ft-cont'>
                <div class='ft-sec2-top'>
                    <p class='ft-terms'>
                        <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/terms/service.php' class='ft-terms-item'>이용약관</a>
                        <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/terms/privacy.php' class='ft-terms-item'>개인정보처리방침</a>                        
                    </p>
                </div>                
            </div>
        </div>
        <div class='ft-section02'>
            <div class='ft-cont ftc-left'>
                <div class='ft-cominfo'>
                    <p class='ft-line'>
                        <span class='ft-name'>(주)자립</span>
                    </p>
                    <p class='ft-line'>
                        <!-- <span class='ft-ceo'>대표 : 김실적</span> -->
                        <span class='ft-add'>서울시 강남구 테헤란로 223,17층</span>
                    </p>
                    <p class='ft-line'>
                        <span class='ft-tel'>Tel : 1688-6721</span>
                        <!-- <span class='ft-fax'>개인정보 책임관리자 : 김실적</span> -->
                        <span class='ft-mail'>E-mail : pantera14@naver.com</span>
                    </p>
                    <p class='ft-line'>
                        <span class='ft-comnum'>사업자등록번호 : 693-13-00627</span>
                        <!-- <span class='ft-salenum'>통신판매업신고번호 : 제2021-0000호</span> -->
                    </p>
                </div>
                <p class='ft-line'>
                    <span class='ft-copyright'>COPYRIGHT ⓒ (주)자립. Design & Development By FITSOFT.</span>
                </p>
            </div>
            <div class='ft-cont ftc-right'>                
                <div class='ft-cscenter'>
                    <p class='ft-line'>
                        <span class='ft-cs'>고객센터</span>
                    </p>
                    <p class='ft-line'>
                        <span class='ft-tel'>1688-6721</span>
                    </p>                    
                </div>
                <div class='ft-logo'><img src='<?=$PATH['RESOURCES']?>/image/siljeok/siljeoklogo.png' alt='siljeok'></div>
            </div>
        </div>
    </div>
</footer>


<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    /* 스크롤탑 */
    $( window ).scroll( function() {
        if ( $( this ).scrollTop() > 200 ) {
            $( '.scroll-top' ).fadeIn();
        } else {
            $( '.scroll-top' ).fadeOut();
        }
    } );
    $( '.scroll-top' ).click( function() {
        $( 'html, body' ).animate( { scrollTop : 0 }, 400 );
        return false;
    } );
    /* 푸터 로고 슬라이드 */
    $(function(){
        footerLogo();
        footerMobile();
        $(window).resize(footerMobile);
    });

    function footerLogo(){
        var swiper = new Swiper(".mySwiper.slider-pc", {
        slidesPerView: 10,
        spaceBetween: 1,
        slidesPerGroup: 1,
        loop: true,
        loopFillGroupWithBlank: true,
        autoplay: {
          delay: 1500,
          disableOnInteraction: false,
        },
      });
    }
    
    function footerMobile(){
        var widthSize = $(window).width();

        
        if(widthSize < 768){
            $('.swiper-container').removeClass('slider-pc');
            $('.swiper-container').addClass('slider-mo');

            var swiper = new Swiper(".mySwiper.slider-mo", {
                slidesPerView: 4,
                spaceBetween: 1,
                slidesPerGroup: 1,
                loop: true,
                loopFillGroupWithBlank: true,
                autoplay: {
                    delay: 1500,
                    disableOnInteraction: false,
                },
            });
        }
        else{
            $('.swiper-container').addClass('slider-pc');
            $('.swiper-container').removeClass('slider-mo');
        }

    }
</script>



</body>
</html>