<?php 
    namespace service;
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/head.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/sidebar.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/header.php";
    
    $gp_GVI = array(
        "data_render_count"      =>   paramVaildCheck("data_render_count", 20),        // 게시물 렌더링 수
        "page_render_count"      =>   paramVaildCheck("page_render_count", 5),         // 페이지 렌더링 수
        "page_selected_idx"      =>   paramVaildCheck("page_selected_idx", 1),         // 현재 페이지 (쪽)
        "page_selected_sheet"    =>   paramVaildCheck("page_selected_sheet", 1),       // 현재 페이지 (장)

        "ip"                     =>   paramVaildCheck("ip", ""),
        "referer"                =>   paramVaildCheck("referer", ""),
        "current_url"            =>   paramVaildCheck("current_url", ""),
        "current_url2"            =>   paramVaildCheck("current_url2", ""),
        "start_date"             =>   paramVaildCheck("start_date", date("Y-m-d", strtotime("-1 years"))),
        "end_date"               =>   paramVaildCheck("end_date", date("Y-m-d")),
        "device_type"            =>   paramVaildCheck("device_type", ""),
        "device_name"            =>   paramVaildCheck("device_name", ""),
        "browser"                =>   paramVaildCheck("browser", ""),
        
        "is_revisit"             =>   paramVaildCheck("is_revisit", ""),

        "sort_list"              =>   paramVaildCheck("sort_list", "visit_date desc"),
    );

    
    // 검색 옵션 목록 도출
    $device_list = Visiter::getOptionList('device_type');
    $device_name_list = Visiter::getOptionList('device_name');
    $browser_list = Visiter::getOptionList('browser');
    $media_list = Visiter::getActionList();
?>
<!------------------------------------------------------- STYLE ----------------------------------------------------------->
<!------------------------------------------------------- STYLE ----------------------------------------------------------->
<style>
    body {}
    .wrap02{padding:70px 0 60px 230px;}
    /* 페이지 인트로 */
    .wrap02 .title{ font-size:16px; margin-bottom: 10px; font-weight:600;}
    .wrap02 .intro{ padding : 30px;}
    .wrap02 .intro > span{font-size:1.3rem;font-weight:600; position:relative; margin-left:15px; display:inline-block;}
    .wrap02 .intro > span::before {content:""; position:absolute; top:50%; left:-11px; display:block; width:4px; height:80%; background-color:#0D2EA3; transform:translateY(-50%);}

    /* 통계 */
    /* 검색 */
    .cont {max-width:1600px; margin:0 30px 30px;}
    .cont-search_board {display:flex; flex-direction:column; padding : 25px 5px; border-radius:4px;}
    .cont-search_board .search-item {display:flex; margin-bottom:6px; align-items:center;/* width:50%; */}
    .cont-search_board .search-item.tit-media {padding:5px 0;}
    /* .cont-search_board .tit-media {display:flex; align-items:center; margin-bottom:6px; width:100%;} */
    .cont-search_board .search-item label {margin-right:6px; padding:5px 0; font-size : 0.9rem;}
    .cont-search_board .search-item label input[type='checkbox'] {margin-right:3px;}
    .cont-search_board .search-item input[type='text'], select {padding:5px; border:1px solid #d5d5d5; border-radius:3px; font-size : 0.9rem;}
    .cont-search_board .search-item input[type='text'].event-search_keyword {width:25%; padding:5px; border:1px solid #d5d5d5; border-radius:5px; font-size : 0.9rem; margin-right:3px;}
    .cont-search_board .search-item input[type='text'].join-date {width:25%; font-size : 0.9rem; margin-right:5px;}
    .cont-search_board .search-item select {width:25%; margin-right:5px;}
    .cont-search_board .search-item .search-item-tit {width:100px; font-weight:500;}
    .cont-search_board .search-item .btn-wrap {padding:0 10px; color:#fff; background-color:#0d2ea3; border-radius:4px; cursor:pointer; font-size:.9rem; padding:5px 10px;}
    .cont-search_board .search-form:nth-of-type(4)  {width:49.2%;}
    .wrap02 .log-cont {margin-top:25px;}    
    .cont-search_board .tit-media > label:nth-of-type(6n)::after {content:"";}


    /* 로그 컨테이너 */
    /* .wrap .log-cont{}
    .wrap .log-cont .table{ table-layout: fixed;width:100%; margin-left:20px; color:#707478; font-size:14px; border-collapse: collapse;text-align:center; border : 1px solid #e2e7eb;  border-radius : 15px}
    .wrap .log-cont .table thead{width:100%; margin:40px; }
    .wrap .log-cont .table thead th{padding: 10px 15px;color: #242a30; font-weight: 600;}
    .wrap .log-cont .table thead tr{border-top : 1px solid #e2e7eb ;  color : #242a30;  border-bottom : 2px solid #aaa;}
    .wrap .log-cont .table thead th:last-child{border-right: 1px solid #e2e7eb;}
    .wrap .log-cont .table tbody{width:100%; margin:40px; }
    .wrap .log-cont .table tbody tr{border-bottom : 1px solid #e2e7eb ; color: #242a30; }
    .wrap .log-cont .table tbody tr:hover{color: #fff !important; background-color: #3d4a5d !important;}
    .wrap .log-cont .table tbody tr td{white-space:nowrap; overflow:hidden; padding: 10px 15px;font-weight: 400;}
    .wrap .log-cont .table tbody tr td.log_referer{text-align:left;padding: 10px;padding-left:30px;}
    .wrap .log-cont .table tbody td{cursor:pointer; word-break: break-all; white-space: pre-wrap;}
    .keepText{width:auto;}
    .log_referer{position:relative; padding-left:30px;}
    .log_referer i {position:absolute; left:10px; margin-right:15px;}
    .log_referer input{position:absolute; left:-10000px;} */

    /* 고객목록 */    
    .table {}
    .table .table-frame.board_column {border-top:1px solid #686868; border-bottom:1px solid #686868;}
    .table .table-frame .tr {display:flex; align-items:center; justify-content:space-between; padding : 10px 0; border-bottom:1px solid #eee;}
    .table .table-frame .tr .tr-item {width:calc(100% / 8); display:flex; align-items:center; justify-content:center;}
    /* .table .table-frame .tr .tr-item.keepText {width:auto;}
    .table .table-frame .tr .tr-item i{cursor:pointer;}
    .table .table-frame .tr .tr-item.log_referer{position:relative;}
    .table .table-frame .tr .tr-item.log_referer i {position:absolute; left:10px; margin-right:15px;}
    .table .table-frame .tr .tr-item.log_referer input{position:absolute; left:-10000px;} */
    .table .table-frame .tr:nth-child(even) {background-color:#f8f8f8;}
    .table .table-frame .tr .tr-item:last-child {border-right:none;}
    .table .table-frame .tr .tr-item:nth-child(1) {width:10%;} /* IP */
    .table .table-frame .tr .tr-item:nth-child(2) {width:4%;} /* Language */
    .table .table-frame .tr .tr-item:nth-child(3) {width:4%;} /* Device */
    .table .table-frame .tr .tr-item:nth-child(4) {width:4%;} /* Platform */
    .table .table-frame .tr .tr-item:nth-child(5) {width:8%;} /* Browser */
    .table .table-frame .tr .tr-item:nth-child(6) {width:10%;} /* 방문 시각 */
    .table .table-frame .tr .tr-item:nth-child(7) {width:25%;} /* 유입 경로 */
    .table .table-frame .tr .tr-item:nth-child(8) {width:25%;} /* 유입 경로 */
    .table .table-frame .tr .tr-item span {display:flex; align-items:center;}
    .table .table-frame .tr .tr-item span i {cursor:pointer;}
    .table .table-frame .tr .tr-item span.sns-item {display:inline-flex; font-size:.9rem;}
    .table .table-frame .tr .tr-item span.sns-item i {display:inline-block; padding:2px; font-size:.6rem; color:#fff; border-radius:2px; margin-right:2px;}
    .table .table-frame .tr .tr-item span.sns-naver i {background-color:#19ce60;}
    .table .table-frame .tr .tr-item span.sns-kakao i {background-color:#fbe300; color:#473737;}
    .table .table-frame .tr .tr-item span.sns-google i {background-color:#cd392d;}
    .table .table-frame .tr .tr-item span.sns-facebook i {background-color:#3b589e;}
    .table .table-frame .tr .tr-item .item-delete {cursor:pointer; color:#e62525; font-size:.9rem; padding:3px 0;}

    .table .table-frame.th {}
    .table .table-frame.th .tr {}
    .table .table-frame.th .tr .tr-item {font-weight:600;}
    .table .table-frame.td {height:470px; overflow-y:scroll;}
    .table .table-frame.td .none-data {display:flex; align-items:center; justify-content:center; padding:30px; font-weight:600;}
    .table .table-frame.td .tr {border-bottom:1px solid #ddd;}
    .table .table-frame.td .tr .tr-item {font-size:.9rem;}
    .table .table-frame.td .tr .tr-item:nth-child(7) {display:inline-block; text-align:unset; justify-content : unset;}
    .board_post {border-bottom:1px solid #eee;}
    .board_post.item-data {padding:10px 0;}

    /* 검색 결과 카운트 */

    .result_count {display:flex; align-items:center; padding:6px 0; font-weight:600;font-size :0.9rem;}
    .result_count span {font-weight:500; margin-left :8px; font-size : 0.9rem;}
    .log-cont .board-top {margin-bottom:10px;}

    /* Chrome, Safari용 스크롤 바 */
    .table-frame::-webkit-scrollbar {display:none;}

    /* 정렬 필터 */
    .sort-item .xi-align-justify, .sort-item .xi-sort-desc, .sort-item .xi-sort-asc{display:none;}    
    .sort-item.sort- .xi-align-justify{display:inline-block;}
    .sort-item.sort-desc .xi-sort-desc{display:inline-block;}
    .sort-item.sort-asc .xi-sort-asc{display:inline-block;}
    
     /* 3. 검색 결과 더보기 */
     .btn-more {width:100%; padding:10px; font-size:.9rem; display:flex; align-items:center; justify-content:center; color:#fff; background-color:#0d2ea3; border-radius:5px;}

    @media screen and (max-width: 768px) {
        .wrap02{padding-left:0px; padding-bottom:60px; padding-top:50px;}
        .wrap02 .intro {padding-right:0;}
    }
</style>
<!------------------------------------------------------- STYLE END ------------------------------------------------------->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">






<div class='wrap02'>
    <!-- 페이지 인트로 -->
    <div class='intro'>
       <span>방문 로그</span>
    </div>

    <div class='cont'>
        <div class='cont-search_board'>
            <div class='search-item search-select'>
                <p class='search-item-tit'>Device</p>
                <select data-key='device_type' data-event_type='change'>
                    <option value=''>전체</option>
                    <?php
                        for($i = 0; $i < count($device_list); $i++)
                        {
                            $category = $device_list[$i];
                            echo "
                                <option value='{$category['device_type']}'>{$category['device_type']}</option>
                            ";
                        }
                    ?>
                </select>
            </div>
            <div class='search-item search-select'>
                <p class='search-item-tit'>Platform</p>
                <select data-key='device_name' data-event_type='change'>
                    <option value=''>전체</option>
                    <?php
                        for($i = 0; $i < count($device_name_list); $i++)
                        {
                            $category = $device_name_list[$i];
                            echo "
                                <option value='{$category['device_name']}'>{$category['device_name']}</option>
                            ";
                        }
                    ?>
                </select>
            </div>
            <div class='search-item search-select'>
                <p class='search-item-tit'>Browser</p>
                <select data-key='browser' data-event_type='change'>
                    <option value=''>전체</option>
                    <?php
                        for($i = 0; $i < count($browser_list); $i++)
                        {
                            $category = $browser_list[$i];
                            echo "
                                <option value='{$category['browser']}'>{$category['browser']}</option>
                            ";
                        }
                    ?>
                </select>
            </div>
            <!-- <div class='search-item search-check tit-media'>
                <p class='search-item-tit'>유입매체</p>
                <?php
                    for($i = 0; $i < count($media_list); $i++)
                    {
                        $media = $media_list[$i];
                        echo "
                            <label><input type='checkbox' data-key='current_url2' data-event_type='change' value='mk={$media['key']}'>{$media['name']}</label>
                        ";
                    }
                ?>
            </div> -->
            <form class='search-item search-text search-form' data-event_type='submit' onsubmit='return false;'>
                <p class='search-item-tit'>IP</p>
                <input class='event-search_keyword' type='text' data-key='ip' placeholder="검색 할 아이피를 입력하세요." />
                <button type='submit' class='btn-wrap'>검색</button>
            </form>
            <form class='search-item search-text search-form' data-event_type='submit' onsubmit='return false;'>
                <p class='search-item-tit'>유입 경로</p>
                <input class='event-search_keyword' type='text' data-key='referer' placeholder="검색 할 유입경로를 입력하세요." />
                <button type='submit' class='btn-wrap'>검색</button>
            </form>
            <!-- <form class='search-item search-text search-form' data-event_type='submit' onsubmit='return false;'>
                <p class='search-item-tit'>접속 페이지</p>
                <input class='event-search_keyword' type='text' data-key='current_url' placeholder="네이버(NaPm=), 구글(gclid=), 다음(mk=cpd)" />
                <button type='submit' class='btn-wrap'>검색</button>
            </form> -->
            <form class='search-item search-text search-form' data-event_type='submit' onsubmit='return false;'>
                <p class='search-item-tit'>방문 시각</p>
                <input type='text' class='join-date' id='search-start_date' data-key='start_date' data-event_type='change'/>
                <input type='text' class='join-date' id='search-end_date' data-key='end_date' data-event_type='change'/> 
                <button type='submit' class='btn-wrap'>검색</button>
            </form>
            <!-- <div class='search-item search-check'>
                <p class='search-item-tit'>옵션</p>
                <label><input type='checkbox' data-key='is_revisit' data-event_type='change' value='1'>재방문</label>
            </div> -->
        </div>
    
    
        <!-- 로그 데이터 -->
        <div class='log-cont cont-board'>
            <div class="board-top">  
                <p class='result_count'>
                    전체 <span id='count-getList01'></span>&nbsp;개
                </p>
            </div>
            <div class='table'>
                <div class='table-frame board_column th'>
                    <div class='tr board_post'>
                        <div class='tr-item'>
                            NO
                            <span class='data-filter sort-item' data-key='idx' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div>
                        <div class='tr-item'>
                            IP
                            <span class='data-filter sort-item' data-key='ip' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div>
                        <div class='tr-item'>
                            Language
                            <span class='data-filter sort-item' data-key='lang' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div>
                        <div class='tr-item'>
                            Device
                            <span class='data-filter sort-item' data-key='device_type' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div>
                        <div class='tr-item'>
                            Platform
                            <span class='data-filter sort-item' data-key='device_name' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div>
                        <div class='tr-item'>
                            Browser
                            <span class='data-filter sort-item' data-key='browser' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div>
                        <div class='tr-item'>
                            방문 시각
                            <span class='data-filter sort-item' data-key='visit_date' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div>
                        <div class='tr-item'>
                            유입 경로
                            <span class='data-filter sort-item' data-key='referer' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div>
                        <!-- <div class='tr-item'>
                            접속 페이지
                            <span class='data-filter sort-item' data-key='current_url' data-event_type='click'>                                
                                <i class='xi-align-justify'></i>
                                <i class='xi-sort-desc'></i>
                                <i class='xi-sort-asc'></i>
                            </span>
                        </div> -->
                    </div>
                </div>
                <div class='table-frame td board_list' id='list-getList01'>
                </div>
            <div id='more-getList01' class="bowler-bot">
                <button class='btn-more btn-large' id='more-01'>더보기</button>
            </div>
            </div>
        </div>
    </div>    
</div>





<!------------------------------------------------------ SCRIPT ----------------------------------------------------------->
<script>
/**************************************************** GLOBAL VARIABLE VALUE DEFINED START *********************************/
    var gp_GVI = {
        data_render_count   :   "<?=$gp_GVI['data_render_count']?>",
        page_render_count   :   "<?=$gp_GVI['page_render_count']?>",
        page_selected_idx   :   "<?=$gp_GVI['page_selected_idx']?>",
        page_selected_sheet :   "<?=$gp_GVI['page_selected_sheet']?>",

        ip                  :   "<?=$gp_GVI['ip']?>",
        referer             :   "<?=$gp_GVI['referer']?>",
        current_url         :   "<?=$gp_GVI['current_url']?>",
        current_url2        :   "<?=$gp_GVI['current_url2']?>",
        
        start_date          :   "<?=$gp_GVI['start_date']?>",
        end_date            :   "<?=$gp_GVI['end_date']?>",
        device_type         :   "<?=$gp_GVI['device_type']?>",
        device_name         :   "<?=$gp_GVI['device_name']?>",
        browser             :   "<?=$gp_GVI['browser']?>",

        is_revisit          :   "<?=$gp_GVI['is_revisit']?>",
        
        sort_list           :   "<?=$gp_GVI['sort_list']?>",
    };
/**************************************************** GLOBAL VARIABLE VALUE DEFINED END ***********************************/
    $(window).on('popstate', function(event) {
        var data = event.originalEvent.state;
        // 해쉬 파라미터와 현재 파라미터 매핑
        addHashMap(gp_GVI, data);
        // 페이지 INIT 호출
        initPackagePage();
    });
    
    $(function(){
        // 검색 이벤트 자동 연동
        autoSetEvent(function(item){
            gp_GVI[item.key] = item.value;
            gp_GVI['page_selected_idx'] = 1;

            getVisitInfo({ isset : true });
        });
        // 정렬 값 자동으로 세팅하기
        autoSetSort(gp_GVI['sort_list']);

        // 페이지 생성
        initPackagePage();

        // 더보기 이벤트 연동
        $('#more-01').on('click', clickMore01);

        // 검색 datepicker
        searchDatepicker("#search-start_date, #search-end_date");
    });

    // 페이지 INIT 함수 (2020.04.06 / By.Chungwon)
    function initPackagePage(){
        // 새로고침 시 페이지 인덱스 저장
        var init_data_render_count = gp_GVI['data_render_count'] * gp_GVI['page_selected_idx'];

        getVisitInfo({ 
            page_selected_idx : 1,
            init_data_render_count : init_data_render_count
        });

        // 파라미터 상태값 HTML과 매핑
        setHTMLMapping();
    }
    // 파라미터 상태값 HTML과 매핑 (2020.04.06 / By.Chungwon)
    function setHTMLMapping(){
        autoSetItem(gp_GVI);
    }

    // 방문자 로그 가져오기 (2020.04.06 / By.Chungwon)
    function getVisitInfo(opt){
        /* 새로고침, 뒤로가기 시 변수 값 저장용 */
        var params = setArrayDimension(gp_GVI);
        var temp_params = $.extend({}, params);

        if(empty(opt) === false && empty(opt['init_data_render_count']) === false){
            temp_params['page_selected_idx'] = opt['page_selected_idx'];
            temp_params['data_render_count'] = opt['init_data_render_count'];
        }

        /* API 호출 */
        sendAPI("/admin/visiter", "getVisitInfo", temp_params, function(res){
            // HTML 관련 변수
            var $data_cont = $('#list-getList01');

            // 수신 파라미터
            var data_list = res['data_list'];
            var data_count = res['data_count'];

            // 반환된 데이터 수 매핑
            $('#count-getList01').text(data_count);

            if(opt['isset'] === true){
                // HTML 리셋
                $data_cont.html('');
            }

            if(data_list.length > 0)
            {
                var str_item = "";
                for(var i = 0; i < data_list.length; i++)
                {
                    str_item += createHTMLVisitLog(data_list[i]);
                }
                $data_cont.append(str_item);

            }else if(gp_GVI['page_selected_idx'] == 1)
            {
                $data_cont.append("<div class='none-data'> 검색된 데이터가 없습니다. </div>");

            }else
            {
                alert('더 이상 불러올 데이터가 없습니다.');
            }

            // 해쉬맵 추가하기
            changeHash('<?=$_SERVER['PHP_SELF']?>', params);
            spinnerOff();
            
            // ajaxSend("<?=$CONTEXT_PATH . $PREFIX['FRONT']?>/util/pagenation.php", "get", temp_params, function(paging_html){
            //     $(".pagenation-cont").html(paging_html);
            // });
        });
    }

    function clipboardCopy(copy){
        var referer = $(copy).nextAll('input').val();
        window.open(referer);
        return;
        $(copy).nextAll('input').select();
        document.execCommand('copy');
        
        autoAlert("주소를 클립보드에 복사했습니다.", 3000, 55);
    }
/**************************************************** SET FUNCTION START **************************************************/    
    
    // datepicker(검색)
    function searchDatepicker(container){
        $(container).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true, // 월을 바꿀수 있는 셀렉트 박스를 표시한다. 
            changeYear: true, // 년
            showOtherMonths: true,
            showMonthAfterYear: true,
            yearSuffix: "년", //달력의 년도 부분 뒤에 붙는 텍스트
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'], //달력의 월 부분 텍스트
            monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], //달력의 월 부분 Tooltip 텍스트
            dayNamesMin: ['일','월','화','수','목','금','토'], //달력의 요일 부분 텍스트
            dayNames: ['일요일','월요일','화요일','수요일','목요일','금요일','토요일'], //달력의 요일 부분 Tooltip 텍스트
            ignoreReadonly : true
        });
    }
    
/**************************************************** SET FUNCTION END ****************************************************/
/**************************************************** EVENT BINDING START *********************************************/
// 더보기 클릭 - 유저  (2020.05.07 / By.Chungwon)
function clickMore01(){
    gp_GVI['page_selected_idx']++;


    // 검색 실행
    getVisitInfo({});
}

/**************************************************** HTML CREATE FUNCTION START ******************************************/
    // 접속자 통계 HTML (2020.04.06 / By.Chungwon)

    function createHTMLVisitLog(log){
        
        var referer = log['referer'];

        
        if(referer !== "Access by URL")
        {
            try{
                if(log['current_url'].indexOf('mk=cpn') !== -1)
                {// 네이버 검색인 경우
                    n_rank = decodeURIComponent(getStrAmong(log['current_url'], 'n_rank=', '&'));
                    n_query = decodeURIComponent(getStrAmong(log['current_url'], 'n_query=', '&'));
                    n_network = decodeURIComponent(getStrAmong(log['current_url'], 'n_network=', '&'));
                    

                    if(n_rank !== "false" && n_query !== "false" && n_network !== "false")
                    {
                        referer = StringFormat("{2} [{0}번째]: {1}", n_rank, n_query, n_network);
                    }
                }
                else if(log['current_url'].indexOf('mk=cpd') !== -1)
                {// 다음 검색인 경우
                    dmcol = decodeURIComponent(getStrAmong(log['current_url'], 'DMCOL=', '&'));
                    dmkw = decodeURIComponent(getStrAmong(log['current_url'], 'DMKW=', '&'));

                    if(dmcol !== "false" && dmkw !== "false")
                    {
                        referer = StringFormat("{0}: {1}", dmcol, dmkw);
                    }
                }
                else if(log['current_url'].indexOf('gclid=') !== -1)
                {// 구글 검색인 경우
                    network = decodeURIComponent(getStrAmong(log['current_url'], 'network=', '&'));
                    keyword = decodeURIComponent(getStrAmong(log['current_url'], 'keyword=', '&'));

                    if(network !== "false" && keyword !== "false")
                    {
                        referer = StringFormat("{0}: {1}", network, keyword);
                    }
                }
            }catch{
                referer = log['referer'];
            }
        }
        



        if(log['current_url'].toLowerCase().indexOf("mk=cpn") !== -1)
        {
            log['current_url'] = "네이버 CPC";
        }
        else if(log['current_url'].toLowerCase().indexOf("gclid=") !== -1)
        {
            log['current_url'] = "구글 CPC";
        }
        else if(log['current_url'].toLowerCase().indexOf("mk=cpd") !== -1)
        {
            log['current_url'] = "다음 CPC";
        }
        else if(log['current_url'].toLowerCase().indexOf("mk=id") !== -1)
        {
            log['current_url'] = "인스타 DM";
        }
        else if(log['current_url'].toLowerCase().indexOf("mk=ts") !== -1)
        {
            log['current_url'] = "티스토리";
        }
        else if(log['current_url'].toLowerCase().indexOf("mk=nb") !== -1)
        {
            log['current_url'] = "네이버 블로그";
        }
        
        var btn_ip_search = log['device_type'] !== "PC" ? "" : StringFormat("<i onclick='window.open(&#39;http://mylocation.co.kr?ip={0}&#39);' class='fas fa-search-location'></i>", log['ip']);

        return StringFormat("\
            <div class='tr board_post item-data'>\
                <div class='tr-item'>{10}</div>\
                <div class='tr-item'>\
                    {9}\
                    {0}\
                </div>\
                <div class='tr-item'>{1}</div>\
                <div class='tr-item'>{2}</div>\
                <div class='tr-item keepText'>{3}</div>\
                <div class='tr-item keepText'>{4}</div>\
                <div class='tr-item'>{5}</div>\
                <div class='tr-item log_referer keepText' title='{6}'>\
                    {7}\
                    <i onclick='clipboardCopy(this);' class='far fa-copy'></i>\
                </div>\
                <!--<div class='tr-item keepText' style='justify-content:flex-start;'>{8}</div>-->\
            </div>\
        "
        ,   log['ip']
        ,   log['lang']
        ,   log['device_type']
        ,   log['device_name']
        ,   log['browser']
        ,   log['visit_date']
        ,   log['referer']
        ,   referer
        ,   log['current_url']
        ,   btn_ip_search
        ,   log['idx']
        );
    }
/**************************************************** HTML CREATE FUNCTION END ********************************************/
</script>
<!------------------------------------------------------ SCRIPT END ------------------------------------------------------->


<?php require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/footer.php"; ?>