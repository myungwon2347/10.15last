<?php
    /*
        사용자 - 실적스토어(자유게시판) > 목록페이지 
        2021.08.31 / By.Chungwon
    */

    /************************************************* 페이지 정보 세팅 *************************************************/
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    $params = isset($_REQUEST['params']) ? json_decode($_REQUEST['params'], true) : null;

    $page_type = "list";              // 페이지 타입 (upload, detail, list)
    $api_url = "/board";      // 요청 API 주소
    
	$target_flag = "Board"; 			// 편의상 API의 ID 키
	$target_table = 'board'; 			// 실제 DB 테이블 명
	$target_idx_name = "board_idx"; 	// 데이터 PK 키 이름
    $target_name = "게시판"; 				// API 명명 (로그 및 출력용)
    
    $type = "profile";
    /************************************************* 접근권한 *************************************************/
    // 페이지 접근권한
    if(empty($_SESSION['login_user']))
    {// 비회원 - 로그인 페이지로 이동
        header("Location:{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/login.php");        
    }
    
    /************************************************* 비즈니스 로직 *************************************************/


    /************************************************* 화면 노출 *************************************************/    
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/layout/head.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/layout/header.php";


    /************************************************* UTIL PHP *************************************************/
?>

<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">

<style>
</style>

<div class='container' id='container-profile-list'>

    <div class='middle-cont'>
        <div class='board-title-area'>
            <h2 class='ta-main-tit'><!-- <i class="go-back mobile xi-angle-left-min" onclick='history.back()'></i> -->프로필게시판</h2>
        </div>

        <!-- 공지사항 목록 -->
        <div class='list-table'>            
            <div id='list-getList<?=$target_flag?>'>
            </div>
            <div id='no-getList<?=$target_flag?>'>
                검색된 데이터가 없습니다.
            </div>
            <?php
                if(isset($_SESSION['login_user']) && ($_SESSION['login_user']['rank'] === "sales" || $_SESSION['login_user']['rank'] === "confirm-sales"))
                {// 영업사원이거나 인증된 영업사원인 경우
                    echo "
                        <div class='list-btn upload-btn'>
                            <a class='keepText' href='{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/board/{$type}/upload.php?type={$type}'>
                                <span>등록하기</span>
                            </a>
                        </div>
                    ";
                }
            ?>
        </div> 

        <div class='pagenation-cont item-data' data-api_name='getList<?=$target_flag?>'></div>

        <div class='board-cate'>    
            <!-- 검색 폼 -->
            <div class='cont-search_board'>
                <div class='tit-search'>
                    <!-- <div class='tit-search-txt'>검색</div> -->
                    <form class='filter-purpose form-search' data-event_type='submit' onsubmit='return false;'>
                        <input class='event-search_keyword' type='text' data-search_key='search_keyword' placeholder='제목을 검색해주세요.'/>
                        <button class='search-btn'>검색</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>









<script>
    /**************************************************** 전역 변수 *********************************************/
    // 파라미터 값 중 0이 유효한 경우, isset 대신 (!== "") 처리
    var g_req = {// 요청 파라미터
        "getList<?=$target_flag?>" : {//  목록 조회 파라미터
            data_render_count           :   "<?=isset($params["getList{$target_flag}"]["data_render_count"]) ? $params["getList{$target_flag}"]["data_render_count"] : "12"?>", // 렌더링 수
            page_render_count           :   Number("<?=isset($params["getList{$target_flag}"]["page_render_count"]) ? $params["getList{$target_flag}"]["page_render_count"] : "5"?>"),  // 선택된 페이지 시트 INDEX            
            page_selected_idx           :   Number("<?=isset($params["getList{$target_flag}"]["page_selected_idx"]) ? $params["getList{$target_flag}"]["page_selected_idx"] : "1"?>"),  // 선택된 페이지 INDEX
            page_selected_sheet         :   Number("<?=isset($params["getList{$target_flag}"]["page_selected_sheet"]) ? $params["getList{$target_flag}"]["page_selected_sheet"] : "1"?>"),  // 선택된 페이지 시트 INDEX            
            sort_list                   :   "<?=isset($params["getList{$target_flag}"]["sort_list"])         ? $params["getList{$target_flag}"]["sort_list"] : "notice_status desc, order_num desc, idx desc"?>",   // 정렬

            type                        :   "<?=$type?>",
            search_keyword              :   "<?=isset($params["getList{$target_flag}"]["search_keyword"])         ? $params["getList{$target_flag}"]["search_keyword"] : ""?>",   // 
        },
    };
    /**************************************************** 전역 변수 끝 *********************************************/


    /**************************************************** 초기화 *********************************************/
    $(function(){
    /***** 페이지 공통 (데이터 불러오기) *****/
        FITSOFT['REST_API']['getInit']({
            func_list : [ 
                "getList<?=$target_flag?>", //  목록 조회 (2021.08.30 / By.Chungwon)
            ],
            complete : function(api_name)
            {// 모든 비동기 함수 종료

                // 검색 이벤트 값과 파라미터 변수 값과 매핑 (2차원 구조로 변경 필요)
                autoSetItem(g_req[api_name], "search_key");

                var new_url = setParamsToUrl(document.location.pathname, { params : JSON.stringify(g_req) });
                history.replaceState({ params : JSON.stringify(g_req) }, null, new_url);

            },
        });
        
    /***** 페이지 공통 (유틸리티 / INIT) *****/
        searchDatepicker('#date-start_date, #date-end_date');
        // 이벤트 연동
        setEventBinding($("[data-method]"));

    /***** 목록페이지 (검색) *****/
        autoSetEvent(function(item)
        {// 검색 변수에 이벤트 바인딩 (2차원 구조로 변경 필요)
            var api_name = $(item).closest('.item-data').data('api_name');
            api_name = empty(api_name) ? 'getList<?=$target_flag?>' : api_name;
            
            g_req[api_name][item.key] = item.value;
            g_req[api_name]['page_selected_idx'] = 1;

            // 변수 값 유지를 위해 URL 해시값 변경
            changeHash(document.location.pathname, { params : JSON.stringify(g_req) });
            // 데이터 호출
            get(api_name, { is_init : true });

        }, 'search_key');  

        // autoSetEvent(function(item)
        // {// 검색 변수에 이벤트 바인딩 (2차원 구조로 변경 필요)
        //     g_req['getList<?=$target_flag?>'][item.key] = item.value;
        //     g_req['getList<?=$target_flag?>']['page_selected_idx'] = 1;

        //     // 변수 값 유지를 위해 URL 해시값 변경
        //     changeHash(document.location.pathname, { params : JSON.stringify(g_req) });
        //     // 데이터 호출
        //     get("getList<?=$target_flag?>", { is_init : true });

        // }, 'search_key');

    /***** 목록페이지 (정렬) *****/
        // 정렬 값을 파라미터 변수 값과 매핑하기 (2차원 구조로 변경 필요)
        autoSetSort(g_req['getList<?=$target_flag?>']['sort_list'], "sort_key");
    });
    $(window).on('popstate', function(event) 
    {// 해시태그가 바뀌는 경우
        if(empty(event.originalEvent.state) === false)
        {
            g_req = JSON.parse(event.originalEvent.state.params);
            get("getList<?=$target_flag?>", { is_init : true });
        }
    });
    $(window).on("pageshow", function(event){
        // event.originalEvent.persisted - BFCache로부터 복원된 경우 true (ex. 뒤로가기)
        // if(event.originalEvent && (event.originalEvent.persisted || (window.performance && window.performance.navigation.type == 2)))
        if(event.originalEvent && event.originalEvent.persisted)
        {// BFCache로부터 복원된 경우 true (ex. 뒤로가기)
            g_req = JSON.parse(event.originalEvent.state.params);
            get("getList<?=$target_flag?>", { is_init : true });
        }
    });
    /**************************************************** 초기화 끝 *********************************************/


    /**************************************************** 정적 바인딩 이벤트 *********************************************/
    function staticMethodHandler(e)
    {// 정적 메소드 핸들러 (2021.05.29 / By.Chungwon)

        // 이벤트 핸들러인 경우
        var target = $(e.currentTarget);
        var api_info = target.data();
        var item = target.closest(".item-data");
        var item_info = item.data();
        var item_siblings = item.parent().find("[data-method=" + api_info['method'] + "]"); // 동일 선상의 형제 값


        if(false) {}

        /********** 목록 페이지 - 동적 이벤트 **********/
        
        
        else if(api_info['method'] === "delete_checked_all")
        {// 삭제 체크박스 전체 선택 (2021.08.31 / By.Chungwon)            

            var status = target.prop('checked');

            var canvas = $("#list-getList<?=$target_flag?>");
            canvas.find("[data-item_key=delete]").prop('checked', status);
        }


        else if(api_info['method'] === "delete_checked")
        {// 체크박스 선택된 데이터 삭제 (2021.08.31 / By.Chungwon)            
            if(confirm("정말로 삭제하시겠습니까?") === false){ return; }

            var canvas = $("#list-getList<?=$target_flag?>");

            var checked_select_list = canvas.find("[data-item_key=delete]:checked");
            var csl_length = checked_select_list.length;

            $(checked_select_list).each(function(index, element){
                var message_idx = $(element).closest('.item-data').data('idx');

                sendAPI("<?=$api_url?>", "delete<?=$target_flag?>Sender", { "<?=$target_idx_name?>" : message_idx }, function(res){
                    
                    if(csl_length === (index + 1))
                    {// 모든 삭제가 끝난 경우
                        location.reload();
                    }                
                });
            });
        }


        else if(api_info['method'] === "more")
        {// 데이터 목록 더보기 클릭 (2021.07.06 / By.Chungwon)
            
            g_req['getList<?=$target_flag?>']['page_selected_idx']++;
            get("getList<?=$target_flag?>", { is_init : false });
        }


        else if(api_info['method'] === "paging")
        {// 페이징 (2021.07.28 / By.Chungwon)
            var api_name = item_info['api_name'];

            var flag = api_info['flag'];
            var state = api_info['state'];

            var page_selected_idx = 1;  // first 인 경우
            var page_selected_sheet = 1;  // first 인 경우

            var paging_options = g_req[api_name];

            if(state === 'first'){
                page_selected_idx = 1;

            }else if(state === 'prev'){
                page_selected_idx = Number(paging_options.page_selected_idx) - 1;

            }else if(state === 'next'){
                page_selected_idx = Number(paging_options.page_selected_idx) + 1;

            }else if(state === 'end'){
                page_selected_idx = Number(paging_options.total_page_count);
            }

            if(flag === 'btn'){
                if(page_selected_idx === 0 || page_selected_idx > paging_options.total_page_count){
                    return 0;
                }
                    
                // 버튼으로 페이지 이동 시에만
                if(state === 'prev' && page_selected_idx % paging_options.page_render_count === 0){
                    // 이전 버튼
                    paging_options.page_selected_sheet = Number(paging_options.page_selected_sheet) - 1;
                }else if(state === 'next' && paging_options.page_selected_idx % paging_options.page_render_count === 0){
                    // 다음 버튼
                    paging_options.page_selected_sheet = Number(paging_options.page_selected_sheet) + 1;
                }else if(state === 'first'){
                    // 처음으로
                    paging_options.page_selected_sheet = 1;
                }else if(state === 'end'){
                    // 마지막으로
                    paging_options.page_selected_sheet = Math.ceil(Number(paging_options.total_page_count) / Number(paging_options.page_render_count));
                }
            }else if(flag === 'index'){
                page_selected_idx = state;
            }  

            if(page_selected_idx === paging_options.page_selected_idx)
            {// 현재 페이지와 같은 경우 이동안함
                return;
            }

            g_req[api_name]['page_selected_sheet'] = paging_options.page_selected_sheet;
            g_req[api_name]['page_selected_sheet'] = paging_options.page_selected_sheet;
            g_req[api_name]['page_selected_idx'] = page_selected_idx;

            changeHash(document.location.pathname, { params : JSON.stringify(g_req) });

            var $canvas = $("#list-" + api_name);
            $canvas.html("");

            get(api_name, { });
        }
    

        else if(api_info['method'] === "move")
        {// 페이지 이동 (2021.07.06 / By.Chungwon)
            var target_idx = item_info['idx'];
            var move_type = api_info['move_type'];
        
            if(move_type === "delete")
            {// 삭제인 경우, 페이지 이동없음.
                if(confirm("정말로 삭제하시겠습니까?"))
                {
                    sendAPI("<?=$api_url?>", "delete<?=$target_flag?>", { "<?=$target_idx_name?>" : target_idx }, function(res){
                        location.reload();
                    });
                }
                return;
            }
            else
            {// 등록/수정 또는 상세 화면 이동

                // 현재 URL
                var current_url = document.location.pathname;

                // list 페이지를 변경되는 페이지로 변경
                var change_url = current_url.replace("list.php", move_type + ".php");
                // var change_url = replaceReverse(current_url, "list.php", move_type + ".php");

                // 페이지로 이동
                location.href = StringFormat("{0}?<?=$target_idx_name?>={1}", change_url, target_idx);
            }
        }
        /********** 목록 페이지 - 동적 이벤트 끝 **********/
    }

    /**************************************************** 정적 바인딩 이벤트 끝 *********************************************/


    /**************************************************** GET 메소드 *********************************************/
    function get(api_name, opt)
    {
        var api_type = "getList";
        var is_init = empty(opt) || empty(opt['is_init']) ? false : opt['is_init'];
        var api_url = empty(opt) || empty(opt['api_url']) ? "<?=$api_url?>" : opt['api_url'];
        var params = empty(opt) || empty(opt['params']) ? g_req[api_name] : opt['params'];

        FITSOFT['REST_API'][api_type]({ 
            api_url : api_url,
            api_name : api_name,
            is_init : is_init,
            params : params,

            callback : function(res)
            {// 리스트 API 콜백

                // 비동기 함수 동기화 처리
                if(empty(opt) === false && empty(opt['init']) === false){ opt['init']({ api_name : api_name}); }

                if(api_name === "getList<?=$target_flag?>")
                {// 페이징 처리
                    g_req[api_name]['total_data_count'] = Number(res['data_count']);
                    g_req[api_name]['total_page_count'] = res['data_count'] === 0 ? 1 : Math.ceil(Number(res['data_count']) / Number(g_req[api_name]['data_render_count']));
                    
                    ajaxSend("<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?>/util/pagenation2.php", "get", { pagingOptions : JSON.stringify(g_req[api_name]) }, function(r2){
                        $(".pagenation-cont").html(r2);

                        setEventBinding($(".pagenation-cont").find("[data-method]"));                
                    });
                }

                for(var i = 0; i < res['data_list'].length; i++){
                    add(api_name, { 
                        item : res['data_list'][i], 
                        is_end : res['data_list'].length === (i+1) ,
                    });
                }
            }
        });
    }
    /**************************************************** GET 메소드 끝 ******************************************/


    /**************************************************** 셋팅 ******************************************/
    function add(api_name, res)
    {// 데이터 추가 메소드 (2021.06.18 / By.Chungwon)

        /******************** 변수세팅 ********************/
        var item = res['item'];
        var is_end = empty(res['is_end']) ? true : res['is_end'];
        var attach = empty(res['attach']) ? 'append' : res['attach'];
        var $canvas = empty(res['canvas']) ? $("#list-" + api_name) : res['canvas'];
        /******************** 변수세팅 끝 ********************/


        /******************** 액션별 분기처리 *******************/
        if(false) {}
        
        else
        {// 그 외 액션
            var html = create(api_name, item);
            $canvas[attach](html);
        }
        /******************** 액션별 분기처리 끝 *******************/



        /******************** 동적 이벤트 바인딩 *******************/
        if(is_end){

            // 이벤트 자동 연동
            setEventBinding($canvas.find("[data-method]"));                
        }
        /******************** 동적 이벤트 바인딩 끝 *******************/
        

        
    }
    /**************************************************** 셋팅 끝 ******************************************/


    /**************************************************** HTML 생성 *********************************************/    
    function create(api_name, data)
    {//  HTML 생성 (2021.07.29 / By.Chungwon) - 수신값은 전부 문자열
        data['api_name'] = api_name;

        if(false){}

        else if(api_name === "getList<?=$target_flag?>")
        {//  HTML 생성 (2021.07.06 / By.Chungwon)

            // [날짜] - 시/분/초 짜르기
            data['insert_date'] = empty(data['insert_date']) ? "" : data['insert_date'].split(" ")[0];

            data['str_is_image'] = data['is_image'] == "0" ? "" : "<img src='<?=$PATH['RESOURCES']?>/image/icon/list-img.png' alt='img-icon'>";
            data['str_is_notice'] = data['notice_status'] == "0" ? "" : "notice";
            
            data['thumbnail'] = empty(data['thumbnail']) ? "<?=$PATH['RESOURCES']?>/image/ex-img/profile.png" : "<?=$PREFIX['FILE']?>" + data['thumbnail'];

            return StringFormat("\
                <div class='table td item-data {10}' {0}>\
                    <a class='keepText' href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/board/<?=$type?>/detail.php?board_idx={1}'>\
                        <div class='table-item-thumb item01'>\
                            <img src='{9}' alt='profile'>\
                        </div>\
                        <div class='table-item-info'>\
                            <p class='table-item item02'>\
                                {2}\
                            </p>\
                            <p>\
                                <span class='table-item item03'>\
                                    {4}\
                                </span>\
                                <span class='table-item item04'>\
                                    {5}\
                                </span>\
                            </p>\
                        </div>\
                    </a>\
                </div>\
            "
            ,   getlistToDataStr(['idx'], data)
            ,   data['idx']
            ,   data['title']
            ,   data['str_is_image']
            ,   data['reg_user_nickname']
            ,   data['insert_date']
            ,   data['like_count']
            ,   data['hit']
            ,   data['reply_count']
            ,   data['thumbnail']
            ,   data['str_is_notice']
            );
        }
    }


    /**************************************************** HTML 생성 끝 *********************************************/
</script>
<?php require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/layout/footer.php"; ?>
