<?php 
    namespace service;
    /*
        관리자 - 팝업 목록 페이지 
        2021.09.28 / By.Chungwon
    */
    use util\Front;

    /************************************************* 페이지 정보 세팅 *************************************************/
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    $params = isset($_REQUEST['params']) ? json_decode($_REQUEST['params'], true) : null;

    $page_type = "list";              // 페이지 타입 (upload, detail, list)
    // $api_url = "/". $login_user['auth_level'] . "/popup";      // 요청 API 주소
    $api_url = "/" . $_SESSION['login_user']['auth_level'] . "/popup";      // 요청 API 주소

	$target_flag = "Popup"; 			// 편의상 API의 ID 키
	$target_table = 'popup'; 			// 실제 DB 테이블 명
	$target_idx_name = "popup_idx"; 	// 데이터 PK 키 이름
    $target_name = "팝업"; 				// API 명명 (로그 및 출력용)
    
    /************************************************* 접근권한 *************************************************/
    // 페이지 접근권한
    // if(empty($_SESSION[$GLOBALS['SITE']['E_NAME']]['login_user']))
    if(empty($_SESSION['login_user']))

    {// 비회원 - 로그인 페이지로 이동
        header("Location:{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['ADMIN']}/page/login.php");        
    }
    // else if($_SESSION[$GLOBALS['SITE']['E_NAME']]['login_user']['auth_level'] !== "admin")
    else if($_SESSION['login_user']['auth_level'] !== "admin")

    {// 회원 - 관리자 계정이 아닌 경우
        header("Location:{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/login.php");        
    }
    
    /************************************************* 비즈니스 로직 *************************************************/
    $item_list = array(// 검색/조회/목록/정렬

        /**** 필터 ****/
        array(
            "key"                       => "title",
            "name"                      => "제목",
            "is_list"                   => true,
            "is_form"                   => true,
        ),
        
        /**** 조회 ****/
        array(
            "key"                       => "start_date",
            "name"                      => "게시일",
            "is_list"                   => true,
            "is_order"                  => true,
        ),
        array(
            "key"                       => "end_date",
            "name"                      => "종료일",
            "is_list"                   => true,
            "is_order"                   => true,
        ),
        array(
            "key"                       => "order_num",
            "name"                      => "우선순위",
            "is_order"                  => true,
            "is_list"                   => true,
        ),
        array(
            "key"                       => "view_status",
            "name"                      => "노출상태",
            "is_list"                   => true,
        ),
        


        
        /**** 조회 ****/
        

        /**** 정렬 ****/


        /**** 페이징 ****/
        array(
            "key"                       => "data_render_count",
            "default"                   => "20",
            "is_param"                  => true,
        ),
        array(
            "key"                       => "page_selected_idx",
            "default"                   => "1",
            "is_param"                  => true,
        ),
        array(
            "key"                       => "page_render_count",
            "default"                   => "10",
            "is_param"                  => true,
        ),
        array(
            "key"                       => "page_selected_sheet",
            "default"                   => "1",
            "is_param"                  => true,
        ),
        array(
            "key"                       => "sort_list",
            "default"                   => "idx desc",
            "is_param"                  => true,
        ),
    );
    // table 정보
    $tb = Front::createListTable($item_list);

    /************************************************* 화면 노출 *************************************************/    
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/head.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/sidebar.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/header.php";


    /************************************************* UTIL PHP *************************************************/
    // require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . "/util/upload_excel.php";
?>


<!------------------------------------------------------- STYLE ----------------------------------------------------------->
<style>
    /* .wrap02{padding-left:230px; padding-bottom:60px; padding-top:50px;} */
 
</style>
<!------------------------------------------------------- STYLE END ------------------------------------------------------->


<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="<?=$PATH['RESOURCES']?>/plugins/js/jquery.battatech.excelexport.js"></script>

<link rel="stylesheet" href="<?=$PATH['RESOURCES']?>/css/admin.css" />
<script src="<?=$PATH['RESOURCES']?>/js/fitsoft_210914.js?<?=$SITE['UPDATEDATE']?>"></script>	
<script src="<?=$PATH['RESOURCES']?>/js/common_210914.js?<?=$SITE['UPDATEDATE']?>"></script>	











<div id='container-list'>
    <div class='wrap02'>
        <!-- 페이지 인트로 -->
        <div class='intro'>
            <span class='intro-tit'><?=$target_name?> 목록</span>
            <div class='write-btn'>
                <a class='upload-btn' href="<?=str_replace("list.php", "upload.php", $_SERVER['PHP_SELF'])?>">+ <?=$target_name?> 등록</a>
                <!-- <a class='upload-btn' onclick="g_excel_form['create']('.wrap02', '<?=$api_url?>', 'upload<?=$target_flag?>');"><i class='xi-upload'></i>엑셀 업로드</a>
                <a class='upload-btn' href='#' download='' onclick="downloadExcel(this, '<?=$target_name?> 목록', 'list-table');"><i class='xi-download'></i> 엑셀 다운로드</a> -->
            </div>
        </div>
        <div class='cont'>
            <div class='cont-top'>
                <div class='top-search'>
                    <div class='search-group'>
                        <?= Front::createListSearch($item_list); ?>
                    </div>
                </div>
            </div>
            <div class='cont-mid'>
                <div class='mid-con'>
                    <div class='table-top'>
                        <div class='table-top-left'>
                            <p class='list-count'>총 <span id='count-getList<?=$target_flag?>''></span> 건</p>
                        </div>
                        
                        
                        <div class='company-align align-data'>
                            <?= Front::createListOrder($item_list); ?>
                        </div>
                    </div>

                    <div class='cont-table'>
                        <table class='table' id='list-table'>
                            <thead class='table-frame th'>
                                <tr class='tr'>
                                    <!-- <th class='tr-item'>번호</th>   -->
                                    <?= $tb['head']; ?>
                                    <th class='tr-item'>상세</th>
                                </tr>
                            </thead>
                            <tbody class='table-frame td' id='list-getList<?=$target_flag?>'>
                            </tbody>
                            <tbody id='no-getList<?=$target_flag?>'>
                                <div class='count-status'>검색된 데이터가 없습니다.</div>
                            </tbody>
                        </table>                    
                    </div>
                    
                    <div class='pagenation-cont item-data' data-api_name='getList<?=$target_flag?>'></div>
                    <!-- <div>
                        <button class='user-more' data-method='more' data-method_event='click'>더 보기<i class='xi-arrow-down'></i></button>
                    </div> -->
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
            <?= Front::createListJSParam($params["getList{$target_flag}"], $item_list); ?>},
    };
    /**************************************************** 전역 변수 끝 *********************************************/
    
    /**************************************************** 초기화 *********************************************/
    $(function(){
    /***** 페이지 공통 (데이터 불러오기) *****/
    
        FITSOFT['REST_API']['getInit']({
            func_list : [ 
                "getList<?=$target_flag?>", //  목록 조회 (2021.07.06 / By.Chungwon)
            ],
            complete : function(api_name)
            {// 모든 비동기 함수 종료

                // 검색 이벤트 값과 파라미터 변수 값과 매핑 (2차원 구조로 변경 필요)
                autoSetItem(g_req[api_name], "search_key");


                var new_url = setParamsToUrl(document.location.pathname, { params : JSON.stringify(g_req) });
                history.replaceState({ params : JSON.stringify(g_req) }, null, new_url);                
            },
        });
        
        // 캘린더 연동
        searchDatepicker('.datepicker');
        // 이벤트 핸들러 연동
        setEventBinding($("[data-method]"));

        // 검색 이벤트 연동
        autoSetEvent(function(item)
        {// 검색 변수에 이벤트 바인딩 (2차원 구조로 변경 필요)
            g_req['getList<?=$target_flag?>'][item.key] = item.value;
            g_req['getList<?=$target_flag?>']['page_selected_idx'] = 1;

            // 변수 값 유지를 위해 URL 해시값 변경
            changeHash(document.location.pathname, { params : JSON.stringify(g_req) });
            // 데이터 호출
            get("getList<?=$target_flag?>", { is_init : true });

        }, 'search_key');

        // 정렬 값 HTML에 연동 (2차원 구조로 변경 필요)
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
            g_req[api_name]['page_selected_idx'] = page_selected_idx;

            changeHash(document.location.pathname, { params : JSON.stringify(g_req) });

            var $canvas = $("#list-" + api_name);
            $canvas.html("");

            get(api_name, { });
        }


        /********** 목록 페이지 - 동적 이벤트 **********/
        else if(api_info['method'] === "more")
        {// 데이터 목록 더보기 클릭 (2021.07.06 / By.Chungwon)
            
            g_req['getList<?=$target_flag?>']['page_selected_idx']++;
            get("getList<?=$target_flag?>", { is_init : false });
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
                    res['data_list'][i]['index'] = i;

                    add(api_name, { 
                        item : res['data_list'][i], 
                        is_end : res['data_list'].length === (i+1) ,
                    });
                }

                // changeHash(document.location.pathname, { params : JSON.stringify(g_req) });
                // changeHash('<?=$_SERVER['PHP_SELF']?>', params);
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
    {//  HTML 생성 (2021.07.06 / By.Chungwon) - 수신값은 전부 문자열
        data['api_name'] = api_name;

        if(false){}

        else if(api_name === "getList<?=$target_flag?>")
        {//  HTML 생성 (2021.07.06 / By.Chungwon)

            // [날짜] - 시/분/초 짜르기
            data['start_date'] = empty(data['start_date']) ? "" : data['start_date'].split(" ")[0];
            data['end_date'] = empty(data['end_date']) ? "" : data['end_date'].split(" ")[0];

            // [상태 값] - 숫자를 텍스트로
            if(data['view_status'] === "0") { data['view_status'] = "숨김"; }
            else if(data['view_status'] === "1") { data['view_status'] = "노출"; }


            // [텍스트] - null 값인 경우 기본값 설정
            data['project_name'] = empty(data['project_name']) ? "연동필요" : data['project_name'];

            // [숫자] - 숫자에 콤마찍기 (세자리마다)
            data['money'] = empty(data['money']) ? data['money'] : "￦" + Number(data['money']).toLocaleString();

            // [사진] - URL 파싱
            // data['thumbnail'] = FITSOFT['IMAGE']['setLink'](data['thumbnail']);

            // 페이징 넘버링 알고리즘 (2021.09.10 / By.Chungwon)
            var remain_page = (Number(g_req[api_name]['total_page_count']) - Number(g_req[api_name]['page_selected_idx'])) + 1; // 전체 페이지 - 현재 페이지 = 남은 페이지
            var start_index = remain_page * Number(g_req[api_name]['data_render_count']); // 남은 페이지 * 데이터 렌더링 수 =
            var remain_index = (Number(g_req[api_name]['data_render_count'])) * Number(g_req[api_name]['page_selected_idx'] - 1);;
            remain_index = Number(g_req[api_name]['total_data_count']) - remain_index;
            data['page_index'] = start_index - (start_index - remain_index) - data['index'];


            return StringFormat("\
                <tr class='tr item-data' {0}>\
                    <!--<td class='tr-item'>{<?= $tb['count'] + 1; ?>}</td>-->\
                    <?= $tb['create_index']; ?><td class='tr-item w-12p'>\
                        <button class='cbi-remove' data-method='move' data-move_type='detail' data-method_event='click'>상세</button>\
                        <button class='cbi-remove' data-method='move' data-move_type='upload' data-method_event='click'>수정</button>\
                        <button class='cbi-remove' data-method='move' data-move_type='delete' data-method_event='click'>삭제</button>\
                    </td>\
                </tr>\
            "
            ,   getlistToDataStr(["idx"], data)
            <?= $tb['create_data']; ?>
            ,   data['page_index'] // $tb['count'] + 1;
            
            );
        }
    }
    /**************************************************** HTML 생성 끝 *********************************************/    
</script>

<?php require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/footer.php"; ?>






