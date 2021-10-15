<style>
    #list-getListPopup{position:relative;z-index:10000;}
    #list-getListPopup img{/*border: 1px solid gainsboro;*/border-bottom:none;}
    #list-getListPopup div{cursor:pointer;font-size:11px;}
</style>

<div id='list-getListPopup'>
</div>

<script>    
    /**************************************************** 전역 변수 *********************************************/
    // 파라미터 값 중 0이 유효한 경우, isset 대신 (!== "") 처리
    var cur_date = getDate();
    var g_req_layer = {// 요청 파라미터
        "getListPopup" : {//  목록 조회 파라미터
            action_flag : "user",
            start_date : cur_date.dateTime,
            end_date : cur_date.dateTime,
        },
        "getFileListPopup" : {//  목록 조회 파라미터
            ref_table                   :   "popup",
        },
    };
    /**************************************************** 전역 변수 끝 *********************************************/


    /**************************************************** 초기화 *********************************************/
    $(function(){
        
        getLayer("getListPopup", { 
            is_init : true,
            api_url : "/popup",
        });
    });

    function staticMethodHandlerLayer(e)
    {// 정적 메소드 핸들러 (2021.09.30 / By.Chungwon)

        // 이벤트 핸들러인 경우
        var target = $(e.currentTarget);
        var api_info = target.data();
        var item = target.closest(".item-data");
        var item_info = item.data();
        var item_siblings = item.parent().find("[data-method=" + api_info['method'] + "]"); // 동일 선상의 형제 값

        if(false) {}

        /********** 목록 페이지 - 동적 이벤트 **********/
        
        
        else if(api_info['method'] === "close-popup")
        {// 팝업 닫기 (2021.09.30 / By.Chungwon)

            if(api_info['item_value'] == "24")
            {// 24시간 닫기인 경우, 쿠키 저장
                setCookie('popup_idx' + item_info['idx'], 'disable', 24);
            }

            target.closest('.list-popup_image').remove();
        }
    }


    /**************************************************** GET 메소드 *********************************************/
    function getLayer(api_name, opt)
    {
        var api_type = "getList";
        var is_init = empty(opt) || empty(opt['is_init']) ? false : opt['is_init'];
        var api_url = empty(opt) || empty(opt['api_url']) ? "/popup" : opt['api_url'];
        var params = empty(opt) || empty(opt['params']) ? g_req_layer : opt['params'];

        FITSOFT['REST_API'][api_type]({ 
            api_url : api_url,
            api_name : api_name,
            is_init : is_init,
            params : params,

            callback : function(res)
            {// 리스트 API 콜백

                // 비동기 함수 동기화 처리
                if(empty(opt) === false && empty(opt['init']) === false){ opt['init']({ api_name : api_name}); }

                for(var i = 0; i < res['data_list'].length; i++){
                    res['data_list'][i]['index'] = i;

                    addLayer(api_name, { 
                        item : res['data_list'][i], 
                        is_end : res['data_list'].length === (i+1) ,
                    });

                    if(api_name === "getListPopup")
                    {
                        getLayer("getFileListPopup", { // 팝업 이미지 불러오기
                            is_init : true,
                            api_url : "/popup",
                        });
                    }
                }
            }
        });
    }
    /**************************************************** GET 메소드 끝 ******************************************/


    /**************************************************** 셋팅 ******************************************/
    function addLayer(api_name, res)
    {// 데이터 추가 메소드 (2021.09.30 / By.Chungwon)

        /******************** 변수세팅 ********************/
        var item = res['item'];
        var is_end = empty(res['is_end']) ? true : res['is_end'];
        var attach = empty(res['attach']) ? 'append' : res['attach'];
        var $canvas = empty(res['canvas']) ? $("#list-" + api_name) : res['canvas'];
        /******************** 변수세팅 끝 ********************/

        /******************** 액션별 분기처리 *******************/
        if(api_name === "getListPopup")
        {// 팝업 틀 생성
            // 쿠키 생성
            var cookie_check = getCookie("popup_idx" + item['idx']);
            if(cookie_check === "disable"){
                return;
            }

            item['bg_color'] = "#fff";
        }
        else if(api_name === "getFileListPopup")
        {// 팝업 이미지 생성
            var $canvas = $("#list-getListPopup .item-data[data-idx='" + item['ref_idx'] + "'] .list-popup_image");

            if($canvas.length < 1){
                return;
            }

            // var render_type = $canvas.closest('.item-data').data('view_type');
            // /************* 데이터 HTML 매핑 *************/
            // if(render_type === 'Slide')
            // {// 슬릭 추가
            //     $canvas.slick('slickAdd', createLayer(api_name, item));
            // }
        }

        if(false) {}       

        else
        {// 그 외 액션
            var html = createLayer(api_name, item);
            $canvas[attach](html);
        }
        /******************** 액션별 분기처리 끝 *******************/

        if(api_name === "getFileListPopup" && item['view_type'] === "Slide")
        {
            var canvas_child = $("#list-getListPopup .item-data[data-idx='" + item['idx'] + "'] .list-popup_image");
            createSlick(canvas_child);
            
        }



        /******************** 동적 이벤트 바인딩 *******************/
        if(is_end){

            // 이벤트 자동 연동
            setEventBinding($("#list-getListPopup").find("[data-method]"), staticMethodHandlerLayer);
        }
        /******************** 동적 이벤트 바인딩 끝 *******************/
    }
    /**************************************************** 셋팅 끝 ******************************************/
    

    /**************************************************** HTML 생성 *********************************************/    
    function createLayer(api_name, data)
    {//  HTML 생성 (2021.09.30 / By.Chungwon) - 수신값은 전부 문자열
        data['api_name'] = api_name;

        if(false){}

        else if(api_name === "getFileListPopup")
        {

            return StringFormat("\
                <div class='item-data' style='width:100%; height:100%;' {0}>\
                    <a href='{2}'>\
                        <img src='{1}' style='width:100%; height:100%;' alt='{3}'>\
                    </a>\
                </div>\
            "
            ,   getlistToDataStr(['idx'], data)
            ,   FITSOFT['IMAGE']['setLink'](data['path'])
            ,   data['value']
            ,   data['o_name']
            ); 
        }

        else if(api_name === "getListPopup")
        {
            return StringFormat("\
                <div class='popup-item item-data {1}' title='{7}' style='background-color:{2}; left: {3}px; top:{4}px; z-index:10000;' {0}>\
                    <div class='list-popup_image' style='width:{5}px; height:{6}px; overflow: hidden;'>\
                        <!--이 영역에 이미지가 추가됌-->\
                        <div style='display: flex; justify-content: space-between;background-color: black;color: #fff;padding: 6px 10px;'>\
                            <div data-method='close-popup' data-method_event='click' data-item_value='24' style='cursor:pointer;'>24시간 동안 다시보지 않기</div>\
                            <div data-method='close-popup' data-method_event='click' data-item_value='0' style='cursor:pointer;'>닫기</div>\
                        </div>\
                    </div>\
                </div>\
                ",
                getlistToDataStr(['idx', 'view_type'], data),
                data['device_type'], // 디바이스 종류 (PC, Mobile, 전체)
                empty(data['bg_color']) ? "#fff" : data['bg_color'],
                data['location_x'],
                data['location_y'],
                data['width'],
                data['height'],
                data['title'],
            ); 
        }
    }


    /**************************************************** HTML 생성 끝 *********************************************/
</script>