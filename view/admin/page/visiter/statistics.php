<?php 
    namespace service;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/head.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/sidebar.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/header.php";
?>
<!------------------------------------------------------- STYLE ----------------------------------------------------------->
<style>
    body {}
    .wrap{padding-left:230px; padding-bottom:60px; padding-top:70px; max-width: 1550px}
    /* 페이지 인트로 */
    .wrap .title{ font-size:16px; margin-bottom: 10px; color: #818a91; font-weight:600;}
    .wrap .intro{padding:30px;}
    .wrap .intro > span{font-size:1.3rem;font-weight:600; position:relative; margin-left:15px; display:inline-block;}
    .wrap .intro > span::before {content:""; position:absolute; top:50%; left:-11px; display:block; width:4px; height:80%; background-color:#0D2EA3; transform:translateY(-50%);}
    
    /* 방문자 수 (상단) */
    .wrap .visit-count-cont{ padding-left:30px;}
    .wrap .visit-count-cont .visit-count-left{display:inline-flex; flex-direction:row; cursor:pointer;}
    .wrap .visit-count-cont .visit-count-left > div{display:flex; flex-direction:column; padding:20px; align-items: center; justify-content: center; border: 1px solid gainsboro; margin-right : 5px; width : 120px; box-shadow :  3px 3px 3px rgba(0, 0, 0, 0.034); border-radius : 4px; background-color : #fff ;}
    .wrap .visit-count-cont .visit-count-left > div > p:nth-child(1){font-size:14px; color:gray;}
    .wrap .visit-count-cont .visit-count-left > div > p:nth-child(2){font-size:24px; color:#333; font-weight:600; padding-top:10px;}
    .wrap .visit-count-cont .visit-count-left > div:nth-child(5){width : 150px;}

    .wrap .visit-container .visit-count-right{margin-bottom : 5px; display: inline-block;}
    .wrap .visit-container .visit-count-right .visiter-log{border: 1px solid gainsboro;padding: 5px 10px;font-weight: 400; cursor:pointer; box-shadow : 3px 3px 3px rgba(0, 0, 0, 0.034); border-radius :4px; text-align : center; display : flex; align-items : center; justify-content : center; background-color : #fff ;}
    .wrap .visit-container .visit-count-right .visiter-log:hover{background-color:#edf0f1;}
    .wrap .visit-container .visit-count-right .visiter-log i{margin-left : 5px;}
    /* 방문자 통계 (중간) */
    .wrap .visit-container { margin : 20px; margin-left:30px;}
    .wrap .statistics-cont{padding:20px; border:1px solid gainsboro; border-radius : 4px; box-shadow :  3px 3px 3px rgba(0, 0, 0, 0.034);background-color : #fff ;}
    .wrap .statistics-cont #statistics{height:360px; }
    /* 상세페이지 통계 (하단) */
    .wrap .detail-cont{padding:20px; border:1px solid gainsboro; margin:20px; display:flex; flex-direction:row;}
    .wrap .detail-cont .detail-cont-left{flex-grow:5; margin-right:20px; border:1px solid gainsboro;}
    .wrap .detail-cont .detail-cont-left .hit-cont{display:flex; flex-direction:column;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-intro{flex-basis:30px; margin-bottom:10px; display:flex; flex-direction:row; border-bottom:1px solid gainsboro; padding:10px 20px; align-items:center;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-intro p:nth-child(1){flex-grow:1; justify-content:flex-start; align-items:center;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-intro p:nth-child(2){flex-basis:80px; justify-content:center; align-items:center; display:flex;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-ctn{flex-grow:1; padding:10px 20px; padding-top:0px;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-ctn .hit-row{display:flex; flex-direction:row; align-items:center; font-size:14px; border-bottom:1px solid gainsboro; padding:12.5px 0;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-ctn .hit-row:hover{cursor:pointer; background-color:#edf0f1;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-ctn .hit-row p{display:flex; align-items:center;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-ctn .hit-row p:nth-child(1){justify-content:flex-start; flex-basis:200px;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-ctn .hit-row p:nth-child(2){justify-content:flex-start; flex-grow:1; padding:0 10px;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-ctn .hit-row p:nth-child(3){flex-basis:80px; justify-content:center;}
    .wrap .detail-cont .detail-cont-left .hit-cont .hit-ctn .hit-row:last-child{border-bottom:0;}
    .wrap .detail-cont .detail-cont-right{flex-basis:400px;}
    .wrap .detail-cont .detail-cont-right .flow-cont{max-width:400px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type{border:1px solid gainsboro; padding:15px 20px; margin-bottom:10px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type .flow-intro{font-size:16px; font-weight:600; margin-bottom:20px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type .flow-type-cont{}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type .flow-type-cont .flow-type-row{ display:flex; flex-direction:row; align-items:center; padding:10px 0; height:45px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type .flow-type-cont .flow-type-row > p{display:flex; justify-content:center; font-size:16px; color:#333;;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type .flow-type-cont .flow-type-row > p:nth-child(1){flex-basis:40px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type .flow-type-cont .flow-type-row > div:nth-child(2){flex-grow:1; background-color: #edf0f1; border-radius: 20px; margin: 0 10px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type .flow-type-cont .flow-type-row > div:nth-child(2) > div{background-color: #3d4a5d; height: 21px; border-radius: 25px;}    
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-type .flow-type-cont .flow-type-row > p:nth-child(3){flex-basis:60px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword{border:1px solid gainsboro; padding:15px 0px; padding-top:0;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .flow-portal-cont{display:flex; flex-direction:row; padding-bottom:0px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .flow-portal-cont > p{padding:15px 0;flex-grow:1; border-bottom:1px solid gainsboro; color:#333; font-size:16px; font-weight:600; align-items:center; justify-content:center;display:flex;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .flow-portal-cont > p:hover{cursor:pointer; background-color:#edf0f1;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .flow-portal-cont > p.active{background-color: #ffffff;border-bottom: none;border-right: 1px solid gainsboro;border-left: 1px solid gainsboro; margin:0 -1px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .intro{border-bottom:none; font-size:16px; font-weight:600;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .flow-tag-cont{display:flex; flex-direction:column; padding:0 20px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .flow-tag-cont > p{padding:10px 15px; background-color:#3d4a5d; margin:10px 0; border-radius: 8px; color: #fff; justify-content: center; align-items: center; max-width:350px;}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .flow-tag-cont > p > span{}
    .wrap .detail-cont .detail-cont-right .flow-cont .flow-keyword .flow-tag-cont > p > span:nth-child(2){background-color: #f1dcdc; padding: 3px 5px; margin-left: 10px; border-radius: 30px; color: #3d4a5d; font-weight: 600;}

    @media screen and (max-width: 768px) {
        .wrap {padding-left:0px; max-width:calc(140px + 768px);}
        .wrap .visit-count-cont {flex-direction:column; padding:0 10px;}
        .wrap .visit-count-cont .visit-count-left {flex-direction:column; width :100%;}
        .wrap .visit-count-cont .visit-count-right .visiter-log {width:100%; text-align:center;}
        .wrap .visit-count-cont .visit-count-left > div {flex-direction:row; padding:10px; justify-content:flex-start; width :100%; margin-bottom : 5px;}
        .wrap .visit-count-cont .visit-count-left > div:nth-child(5) {width : 100%; margin-bottom : 0;}
        .wrap .visit-count-cont .visit-count-left > div > p:nth-child(1) {width:60%;}
        .wrap .visit-count-cont .visit-count-left > div > p:nth-child(2) {padding-top:0;}
        .wrap .visit-count-cont .visit-count-left > div > p {width :50% ; text-align : center;}
        .wrap .visit-container {margin : 20px 10px;}
    }
</style>
<!------------------------------------------------------- STYLE END ------------------------------------------------------->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">









<div class='wrap'>
    <!-- 페이지 인트로 -->
    <div class='intro'>
       <span>사이트 방문 지표</span>
    </div>
    <!-- 방문자 횟수 및 방문 로그 버튼 -->
    <div class='visit-count-cont'>
        <!-- 좌측 영역 -->
        <div class="visit-count-left">
            <!-- 오늘 방문수 -->
            <div>
                <p>오늘 방문수</p>
                <p id="today_visit"></p>
            </div>
        
            <!-- 어제 방문수 -->
            <div>
                <p>어제 방문수</p>
                <p id="yesterday_visit"></p>
            </div>

            <!-- 재 방문수 -->
            <div>
                <p>재 방문수</p>
                <p id="real_visit"></p>
            </div>

            <!-- 순 방문수 -->
            <div>
                <p>순 방문수</p>
                <p id="re_visit"></p>
            </div>
            
            <!-- 누적 방문수 -->
            <div>
                <p>누적 방문수</p>
                <p id="total_visit"></p>
            </div>

        </div>
    </div>
    <!-- 방문자 통계 -->
    <div class='visit-container'>
    <!-- 우측 영역 -->
        <div class="visit-count-right">
            <!-- 방문 로그 -->
            <div class="visiter-log" onclick="location.href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['ADMIN']?>/page/visiter/visitlog.php'">
                    방문 로그
            <i class="fas fa-signal"></i>
            </div>
        </div>
        <div class="visit-count-right">
            <!-- 7일 보기 -->
            <div class="visiter-log" onclick="getVisitLog(7);">
                    7일 방문 그래프
            <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="visit-count-right">
            <!-- 30일 보기 -->
            <div class="visiter-log" onclick="getVisitLog(30);">
                    30일 방문 그래프
            <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="visit-count-right">
            <!-- 365일 보기 -->
            <div class="visiter-log" onclick="getVisitLog(365);">
                    1년 방문 그래프
            <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="visit-count-right">
            <!-- 3년 보기 -->
            <div class="visiter-log" onclick="getVisitLog(1095);">
                    3년 방문 그래프
            <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class='statistics-cont'>
            <div id="statistics">
            </div>
        </div>
    </div>
</div>










<!------------------------------------------------------ SCRIPT ----------------------------------------------------------->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
/**************************************************** INITIALIZE FUNCTION START *******************************************/   
    $(window).on('popstate', function(event) {
        var data = event.originalEvent.state;
        // addHashMap(g_getAgentList, data);
    });
    $(function(){
        // 방문자 통계 가져오기
        getVisitStatics();

        // google line chart 그리기 - 한달간 방문자 수 
        google.charts.load('current', {'packages':['line']});
        google.charts.setOnLoadCallback(getVisitLog);
    });
/**************************************************** INITIALIZE FUNCTION END *********************************************/
/**************************************************** GET FUNCTION START **************************************************/
    // 방문자 통계 가져오기 (2020.04.02 / By.Chungwon)
    function getVisitStatics(){
        sendAPI("/admin/visiter", "getVisitStatics",{}, function(res){
            $('#today_visit').text(res.today_visit);
            $('#yesterday_visit').text(res.yesterday_visit);
            $('#total_visit').text(res.total_visit);
            $('#real_visit').text(res.real_visit);
            $('#re_visit').text(res.re_visit);
        });
    }
    // 한달간 방문자 수 가져오기 (2020.04.02 / By.Chungwon)
    function getVisitLog(day_count){
        day_count = empty(day_count) ? 30 : day_count;

        sendAPI("/admin/visiter", "getVisitLog",{ day_count : day_count }, function(res){
            var logList = res.visit_count_list;

            //날짜형식 변경하고 싶으시면 이 부분 수정하세요.
            var chartDateformat     = "MM월dd일"; //'yyyy년MM월dd일';
            //라인차트의 라인 수
            var chartLineCount    = 10;
            //컨트롤러 바 차트의 라인 수
            var controlLineCount    = 10;

            var data = new google.visualization.DataTable();
            data.addColumn('date', '');
            data.addColumn("number", "일간 방문수");
            
            var itemList = [];
            logList.forEach(function(item, index, list){
                itemList.push([new Date(item.date), Number(item.count)]);
            });
            data.addRows(itemList);

            var options = {
                legend : { position: "none" },
                hAxis : {
                    format: chartDateformat, 
                    gridlines:{
                        count:chartLineCount,   // 라인 수
                    },
                    textStyle: {fontSize:12},
                },
                focusTarget: 'category',
                tooltip :{
                    isHtml : true
                },
            };


            var chart = new google.charts.Line(document.getElementById('statistics'));

            chart.draw(data, google.charts.Line.convertOptions(options));
        });
    }

/**************************************************** GET FUNCTION END ****************************************************/
</script>
<!------------------------------------------------------ SCRIPT END ------------------------------------------------------->

<?php require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/footer.php"; ?>