<?php 
    namespace service;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    // require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/head.php";
    // require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/sidebar.php";
    // require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/header.php";
?>

<style>
    /* 레이어 팝업 */
    .cont-layer-popup {display: flex; background-color: #ccc;}
    .cont-layer-popup .popup-item {background-color: #fff; position: fixed; z-index: 100;}
    .cont-layer-popup .popup-item .view_state {display: flex; justify-content: space-between;}
    .cont-layer-popup .popup-item .view_state > span {cursor: pointer;}
</style>
<!------------------------------------------------------- STYLE END ------------------------------------------------------->
<!-- Slick -->
<link rel="stylesheet" type="text/css" href="/resources/plugins/css/slick.css"/>
<script type="text/javascript" src="/resources/plugins/js/slick.min.js"></script>


<div class='wrap02'>
    <!-- 페이지 인트로 -->
    <div class='intro'>
        <span>팝업창 띄우기</span>
    </div>
    <div class='cont'>
        <!-- 팝업, 사실상 body에 붙여지게 됨. -->
        <div class='cont-layer-popup' id="list-getList01">

        </div>
    </div>
</div>


<script>
    /**************************************************** 초기화 *********************************************/
    $(function(){
        getListPopup();
        $('.oder-date-pic').on('click', oderDatePic);
    });
    function oderDatePic(){
        $('.oder-date-pic').datepicker({
            dateFormat: 'yy-mm',
            changeYear: true,
            showOtherMonths: true,
            showMonthAfterYear: true,
            yearSuffix: "년", //달력의 년도 부분 뒤에 붙는 텍스트
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'], //달력의 월 부분 텍스트
            monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], //달력의 월 부분 Tooltip 텍스트
            dayNamesMin: ['일','월','화','수','목','금','토'], //달력의 요일 부분 텍스트
            dayNames: ['일요일','월요일','화요일','수요일','목요일','금요일','토요일'], //달력의 요일 부분 Tooltip 텍스트
            minDate:"-50Y",
            maxDate:"Today",
            ignoreReadonly : true
        });
    }
    //****************************************** 데이터 불러오기 ******************************************//
    // 팝업 목록 (2020.07.23 / By.Minseo)
    function getListPopup(option){
        FITSOFT['REST_API']['getList']({ 
            api_url : "/common/popup",
            api_name : "getList",
            is_init : empty(option) ? false : option['is_init'],

            params : {
                data_render_count       :   20,
                page_selected_idx       :   1,
            },
            count : $("#count-getList1"),
            view_state : $(".count-status"),
            callback : function(res)
            {// 리스트 API 콜백
                if(empty(option) === false && empty(option['init']) === false){
                    option['init']();
                }
                
                for(var i = 0; i < res['data_list'].length; i++){
                    var item = res['data_list'][i];

                    // 데이터 HTML 매핑
                    addInfo1({ 
                        item : item, 
                        is_end : res['data_list'].length === (i+1),
                    });
                }
                changeHash('<?=$_SERVER['PHP_SELF']?>', gp_1);
            }
        });
    }
    // 팝업 사진 목록 (2020.07.23 / By.Minseo)
    function getList02(data){
        sendAPI("/common/popup", "getFileList", { type : 'image', ref_table : 'popup', ref_key : 'common' }, function(res){
            // HTML 관련 변수
            var $data_cont = $("#list-getList01 .item-data[data-idx='" + data['idx'] + "']");
            // 수신 파라미터
            var data_list = res['data_list']; 
            var data_count = res['data_count'];

            $data_cont.find('.list-imgList01').html('');

            if(data_list.length > 0)
            {
                var str_item = "";
                for(var i = 0; i < data_list.length; i++){
                    // ref_idx 값과 idx값이 같을 경우 실행
                    if(data['idx'] == data_list[i]['ref_idx']){
                        str_item += createHTML02(data_list[i], data);
                    }
                }
                $data_cont.find('.list-imgList01').append(str_item);
                bindingSlick();
            }

            spinnerOff();
        });
    }
    //****************************************** 셋팅 ******************************************//    
    function addInfo1(res)
    {// 아이템 세팅 (2020.07.24 / By.Chungwon)
        var item = res['item'];
        var is_end = empty(res['is_end']) ? true : res['is_end'];
        var attach = empty(res['attach']) ? 'append' : res['attach'];

        var cookie_check = getCookie("popup_idx" + item['idx']);

        if(cookie_check !== "disable"){
            str_item += createHTML01(item);
            getList02(item);
        }

        var $canvas = $('#list-getList1');

        /************* 데이터 HTML 매핑 *************/
        $canvas[attach](createHTMLFile1(item));

        /********** [이벤트 바인딩] **********/
        if(is_end){
            // 팝업 닫기
            $canvas.find('.btn-delete_popup01').off('click').on('click', deletePopup01);
            // 24시간 닫기
            $canvas.find('.btn-delete_popup02').off('click').on('click', deletePopup02);
        }
    }    
    //****************************************** 이벤트 바인딩 ******************************************//
    // 슬릭 연결 (2020-07-23 / By.Minseo)
    function bindingSlick(){
        $('.list-imgList01').slick();
    }
    // 팝업 닫기 (2020-07-23 / By.Minseo)
    function deletePopup01(e){
        var elist = getEventData(e, 'item-data');
        var popup_idx = elist['idx'];

        var $data_cont = $("#list-getList01 .item-data[data-idx='" + popup_idx + "']");
        $data_cont.remove();
    }
    // 팝업 24시간 닫기/쿠키 설정 (2020-07-23 / By.Minseo)
    function deletePopup02(e){
        var elist = getEventData(e, 'item-data');
        var popup_idx = elist['idx'];

        var $data_cont = $("#list-getList01 .item-data[data-idx='" + popup_idx + "']");
        $data_cont.remove();

        setCookie('popup_idx' + popup_idx, 'disable', 24);
    } 
    // 더보기 클릭 (2020.06.26 / By.Chungwon)
    function clickMore01(){
        gp_1['page_selected_idx']++;

        // 검색 실행
        getList01({});
    }
    // 게시판 상세보기 (2020.06.26 / By.Chungwon)
    function clickDetail01(e){
        var elist = getEventData(e, 'item-data');
        var board_idx = elist['idx'];

        // location.href = '<?=$PATH['HTTP_ROOT']?>/page/user/qna/detail.php?board_idx=' + board_idx;
    }
    // 삭제 - 팝업  (2020.07.22 / By.Minseo)
    function deleteProduct(e){
        if(confirm('정말로 삭제하시겠습니까?') === false){
            return;
        }

        var data = getEventData(e, 'tr');

        sendAPI("/common/popup", "delete", { product_idx : data['product_idx']}, function(res){
            if(res.delete_state){
                location.reload();
            }
        });
    }
    /**************************************************** HTML 생성 *********************************************/
    // 게시판 목록 HTML 생성 (2020.07.23 / By.Minseo)
    function createHTML01(data){
        // data-column 문자열 리스트 가져오기
        var data_str = getlistToDataStr(['idx'], data);
        

        data['res_image_path'] = check_no_image(data['res_image_path']);
        return StringFormat("\
            <div class='popup-item item-data' style='margin-right: 10px; left: {1}px; top: {2}px;' {0}>\
                <div class='list-imgList01'>\
                </div>\
                <div class='view_state'>\
                    <span class='btn-delete_popup02'>오늘 하루 보지 않기</span><span class='btn-delete_popup01'>닫기</span>\
                </div>\
            </div>\
        "
        ,   data_str
        ,   data['location_X']
        ,   data['location_Y']
        );
    }   

    // 게시판 목록 HTML 생성 (2020.07.23 / By.Minseo)
    function createHTML02(data01, data02){
        // data-column 문자열 리스트 가져오기
        var data_str = getlistToDataStr(['idx'], data01);

        return StringFormat("\
                <div class='slide'>\
                    <img src='/upload/{1}' alt='' style='width: {2}px; height: {3}px;'>\
                </div>\
            "
            ,   data_str
            ,   data01['path']
            ,   data02['size_X']
            ,   data02['size_Y']
        );
    }   
    
</script>

<?php require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/footer.php"; ?>