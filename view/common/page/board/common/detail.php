<?php
    /*
        사용자 - 실적스토어(자유게시판) > 상세페이지 
        2021.10.11 / By.smw
    */

    /************************************************* 페이지 정보 세팅 *************************************************/
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    $params = isset($_REQUEST['params']) ? json_decode($_REQUEST['params'], true) : null;

    $page_type = "detail";              // 페이지 타입 (upload, detail, list)
    $api_url = "/board";      // 요청 API 주소
    
	$target_flag = "Board"; 			// 편의상 API의 ID 키
	$target_table = 'board'; 			// 실제 DB 테이블 명
	$target_idx_name = "board_idx"; 	// 데이터 PK 키 이름
    $target_name = "게시판"; 				// API 명명 (로그 및 출력용)
    
    $target_idx = isset($_REQUEST[$target_idx_name]) ? $_REQUEST[$target_idx_name] : null;
    $type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "null";

    /************************************************* 화면 노출 *************************************************/    
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/layout/head.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/layout/header.php";
    // require_once $PATH['SERVER_ROOT'] . "/head.php";
    // require_once $PATH['SERVER_ROOT'] . "/header.php";


    /************************************************* UTIL PHP *************************************************/
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">

<style>
</style>
<div class='container' id='container-basic-detail'>

    <div id='<?=$page_type?>-get<?=$target_flag?>' class='middle-cont item-data'>
        <div class='board-title-area'>
            <h2 class='ta-main-tit'><!-- <i class="go-back mobile xi-angle-left-min" onclick='history.back()'></i> -->실적스토어</h2>
        </div>
        <!-- 1:1 문의 상세 -->
        <div class='list-table'>
            <div class='table th'>
                <p class='table-item item01' data-<?=$page_type?>_key='title'></p>
                <p class='table-item-info'>
                    <span class='table-item item02' data-<?=$page_type?>_key='reg_user_name'></span>            
                    <span class='table-item item03' data-<?=$page_type?>_key='insert_date'></span>            
                    <span class='table-item item04'>조회수<span data-<?=$page_type?>_key='hit'></span></span>           
                </p> 
            </div>
            <div class='table td'>
                <p class='table-item' data-<?=$page_type?>_key='content'></p>
            </div>
        </div>
        
        <!-- 등록 버튼 -->
        <div class='list-btn'>
            <div class='list-btn list-btn comm-btn2'>
                <a data-method='move' data-move_type='list' data-method_event='click'>
                    <span>목록</span>
                </a>
            </div>
        </div>
    </div>
</div>






























<!-- SCRIPT -->
<script>
    /**************************************************** 전역 변수 *********************************************/
    var g_req = {// 요청 파라미터
        "get<?=$target_flag?>" : {// 게시물 상세 조회
            <?=$target_idx_name?> :   <?=$target_idx?>,
        },
        "getListReply" : {// 댓글 목록 조회
            sort_list                   :   "<?=isset($params["getListReply"]["sort_list"])         ? $params["getListReply"]["sort_list"] : "idx asc"?>",   // 정렬

            ref_table          :   "<?=$target_table?>",
            ref_idx            :   <?=$target_idx?>,
        },
        // "getFileList<?=$target_flag?>" : {// 파일 목록 조회
        //     target_idx                  :   <?=$target_idx?>,
        //     ref_table                   :   "<?=$target_table?>",
        // },
    };    
    var g_res = {}; // 수신 파라미터
    var g_multi_list = {}; // 참조하는 데이터
    var g_image_list = {// 이미지 컨테이너
        editor_upload_image_idx : [],   // 에디터 이미지 목록
        delete_file_idx_list : [],   // delete idx list (전체 통합)
    };
    /**************************************************** 초기화 *********************************************/
    $(function(){
        FITSOFT['REST_API']['getInit']({
            func_list : [ 
                "get<?=$target_flag?>", // 게시물 상세 조회
                "getListReply",         // 댓글 목록 조회
                // "getFileList<?=$target_flag?>", // 파일 목록 조회
            ],
            complete : function(api_name)
            {// 모든 비동기 함수 종료
            },
        });
        // 이벤트 연동
        setEventBinding($("[data-method]"));
    });
    /**************************************************** 초기화 끝 *********************************************/






























    /**************************************************** 정적 바인딩 이벤트 *********************************************/
    function staticMethodHandler(e)
    {// 정적 메소드 핸들러

        // 이벤트 핸들러인 경우
        var target = $(e.currentTarget);
        var api_info = target.data();
        var item = target.closest(".item-data");
        var item_info = item.data();
        var item_siblings = item.parent().find("[data-method=" + api_info['method'] + "]"); // 동일 선상의 형제 값

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
                if(empty(opt) === false && empty(opt['init']) === false){
                    opt['init']();
                }

                if(is_init && api_name === "getListReply"){ return; }
                else
                {
                    for(var i = 0; i < res['data_list'].length; i++){
                        add(api_name, { 
                            item : res['data_list'][i], 
                            is_end : res['data_list'].length === (i+1) ,
                        });
                    }
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
        else if(api_name === "get<?=$target_flag?>")
        {// 상세 정보 조회 (2021.07.21 / By.Chungwon)

            /********** 데이터 파싱 **********/
            // item['insert_date'] = item['insert_date'].split(" ")[0];
            // item['content'] = htmlEscape(item['content']);

            // 값 바인딩
            g_res[api_name] = item;

            // 상세 메인 데이터 설정 
            $("#<?=$page_type?>-" + api_name).data(item);

            autoSetItem(g_res[api_name], "<?=$page_type?>_key"); // 세팅 값

        }










        else if(api_name === "getFileList<?=$target_flag?>")
        {// 이미지 일괄처리 예제
            
            /***** 이미지 일괄 등록인 경우 *****/
            if(empty(item['idx']))
            {// 새로 추가된 아이템 인 경우 -> 유효성 검사

                /***** 유효성 검사 및 필터링 *****/
                var img_list = $canvas.find('.item-data');

                for(var i = 0; i < img_list.length; i++)
                {
                    var o_name = $(img_list[i]).data('o_name');
                    var type = $(img_list[i]).data('ref_key');

                    if(item['o_name'] === o_name)
                    {// 새로 추가된 아이템 인 경우
                        alert("이미 등록된 파일명이 존재합니다.");

                        if(g_image_list[type] !== undefined)
                        {// file 데이터 제거하기
                            var temp = Array.from(g_image_list[type]);
                        
                            deleteListFromValue2(temp, "name", o_name);
                            g_image_list[type] = temp;
                        }

                        spinnerOff();
                        return;
                    }
                }
            }










            else
            {// DB 데이터 인 경우 -> 데이터 동기화
                // g_multi_list[api_name]['origin'].push(item['idx']);

                // 에디터 변수에 값 동기화
                if(item['ref_key'] === "editor")
                {
                    g_image_list['editor_upload_image_idx'].push(item['idx']);
                }
            }
            /***** 이미지 일괄 등록인 경우 끝 *****/










            if(item['view_type'] === 'bg' 
                || item['ref_key'] === 'thumbnail'
            )
            {// 배경화면에 추가하는 경우 (1개)
                var $file = $(".image-bg_cont [data-file_key='" + item['ref_key'] + "']");

                // file 객체의 컨테이너
                var $file_parent = $file.closest(".image-bg_cont");
                // 캔버스 (버튼)
                var $canvas = $file_parent.find('.image-bg_set_btn');
                // 이미지 경로
                var path = FITSOFT['IMAGE']['setLink'](item['path']);
                // 백그라운드 및 이미지
                var tag_name = $canvas.prop('tagName').toLowerCase();

                if(tag_name === "img")
                {// 이미지인 경우
                    $canvas.attr('src', path);
                }else
                {// 그 외
                    $canvas.css('background-image', "url('" + path + "')");
                }
                

                // data 매핑
                $file_parent.data('idx', item['idx']);
                $file_parent.data('ref_key', item['ref_key']);
                // active 처리                        
                $file_parent.addClass('active');
            }









            
            else if(item['view_type'] === 'list' 
                || item['ref_key'] === 'director'
                )
            {// 컨테이너에 추가하는 경우 (N개)

                api_name += "-" + item['ref_key'];

                var html = create(api_name, item);
                $canvas = $("#list-" + api_name);
                $canvas[attach](html);

                // 이벤트 자동 연동 (캔버스가 바뀌는 경우에는 이벤트 연동을 다시 해줘야함.)
                setEventBinding($canvas.find("[data-method]"));
            }
        }

        else
        {// 그 외 액션 !!!! create가 필요한 경우에는 여기에 !!!!
            var html = create(api_name, item);
            $canvas[attach](html);
        }
        /******************** 액션별 분기처리 끝 *******************/





        /******************** 동적 이벤트 바인딩 *******************/
        if(is_end){ if(false){}

            // 이벤트 자동 연동
            setEventBinding($canvas.find("[data-method]"));                
        }
        /******************** 동적 이벤트 바인딩 끝 *******************/
    }





    /**************************************************** 셋팅 끝 ******************************************/

</script>
<!-- SCRIPT END -->
<?php require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/layout/footer.php"; ?>