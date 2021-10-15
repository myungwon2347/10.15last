/* 공통 */
$(function(){
})
var FITSOFT = {};

FITSOFT['REST_API'] = {
    getInit : function(params)
    {// 페이지 로딩 (2021.06.04 / By.Chungwon)
        var func_list = params['func_list'];
        var complete = params['complete'];
        
        // 동기화 확인용 변수
        var sync_count = func_list.length;
        
        if(sync_count < 1)
        {// INIT 함수가 없는 경우
            g_page_init_complete = true;
            spinnerOff();
            complete();
        }
        else
        {// INIT 함수가 있는 경우
            // 스피너 동작
            spinnerOn();

            for(var i = 0; i < sync_count; i++)
            {// 비동기 함수 반복
                get(func_list[i], 
                {// 아래 파라미터가 opt 파라미터입니다.
                    init : function(res)
                    {// 비동기 동기화 체크
                        if(--sync_count < 1)
                        {// 모든 비동기 함수 동기화 완료
                            g_page_init_complete = true;

                            var api_name = (empty(func_list[i]) && empty(res)) === false ? res['api_name'] : func_list[i];

                            spinnerOff();
                            complete(api_name);
                        }
                    },
                    is_init : true,
                });
            }
        }
    },
    getList : function (res) 
    {/*
        데이터 목록 가져오기 (2021.06.04 / By.Chungwon)
    */

        var api_url = empty(res['api_url']) ? null : res['api_url'];
        var api_name = empty(res['api_name']) ? null : res['api_name'];

        // 데이터 수를 입력할 엘리먼트 (선택)
        var $count = $("#count-" + api_name);
        $count = empty(res['count']) ? $count : res['count'];

        // 데이터 존재 유무에 따라 뷰 상태가 바뀌는 엘리먼트 (선택)
        var $view_state = $("#no-" + api_name);
        $view_state = empty(res['view_state']) ? $view_state : res['view_state'];

        // 초기 불러오기 여부 (선택)
        var is_init = empty(res['is_init']) ? false : res['is_init'];

        // 서버로 전송 할 데이터 목록 (선택)
        var params = empty(g_req[api_name]) ? res['params'] : g_req[api_name];

        // 요청지 콜백 (선택)
        var callback = empty(res['callback']) ? function(){} : res['callback'];

        // 리스트를 붙일 Jquery 엘리먼트 (선택)
        var canvas = $("#list-" + api_name);
        canvas = empty(res['canvas']) ? canvas : res['canvas'];

        // 불러올 데이터가 없는 경우 alert 출력 여부
        var is_alert = empty(res['is_alert']) ? true : res['is_alert'];

        // 스피너 동작 여부
        var is_spinner = empty(res['is_spinner']) ? false : res['is_spinner'];
        
        params = setArrayDimension(params);

        if(is_init)
        {
            params['page_selected_idx'] = empty(params['page_selected_idx']) ? 1 : params['page_selected_idx'];
            // params['data_render_count'] = empty(params['data_render_count']) ? 20 : (params['data_render_count'] * params['page_selected_idx']);
            params['data_render_count'] = empty(params['data_render_count']) ? 20 : params['data_render_count'];
            // params['page_selected_idx'] = 1;

            if(empty(canvas) === false)
            {// 캔버스가 있는 경우 초기화
                canvas.html("");
            }
        }

        if(is_spinner) {
            spinnerOn();
        }

        // 서버로 데이터 요청
        sendAPI(api_url, api_name, params, function(res)
        {
            if(empty($count) === false)
            {// 데이터 수 설정
                $count.text(res['data_count']);
            }

            // 데이터 없을 때
            if(empty(res['data_list']) === false && res['data_list'].length > 0)
            {                
                empty($view_state) ? "" : $view_state.addClass("disabled");

            }else if(empty(res['data_list']) || res['data_list'].length < 1)
            {
                empty($view_state) ? "" : $view_state.removeClass("disabled");

            }else if(params['page_selected_idx'] == 1)
            {
                // empty($view_state) ? "" : $view_state.addClass("disabled");

            }else
            {
                if(is_alert){
                    alert('더 이상 불러올 데이터가 없습니다.');
                    // alert('There is no more data to load.');                    
                }
            }
            callback(res);
        });
    },
    
}
/* 공통 끝 */


var fit_data_resources = '/resources';
var fit_prefix_file = '/upload';

/* 이미지 */
FITSOFT['IMAGE'] = {
    setLink : function (file_url) 
    {// 이미지 경로를 서버 폴더랑 매핑 (2020.07.17 / By.Chungwon)
        
        if(empty(file_url) || file_url == false)
        {
            return fit_data_resources + "/image/no_image.svg";
        }
        else if(file_url.indexOf('data:') != -1)
        {
            return file_url;
        }
        else if(file_url.indexOf('http') != -1)
        {
            return file_url;
        }
        // return FITSOFT.DATA['SITE']['LINK'] + FITSOFT.DATA['PREFIX']['FILE'] + file_url;
        return fit_prefix_file + file_url;
    },
    changeEvent : function (e, callback, format) 
    {// 파일 객체 변경 이벤트 (2020.07.24 / By.Chungwon)
        if(empty(g_image_list)){
            alert("g_image_list is undefined\n관리자에게 문의해주세요.");
        }
        var sync_count = 0;
        var file_list = e.target.files;
        var image_info = $(e.currentTarget).data();
        var image_key = empty(image_info['file_key']) ? image_info['key'] : image_info['file_key'];
        var type = image_info['type'];
        var format = empty(image_info) ? ['jpg','jpeg','png','gif','bmp','BMP','JPG','JPEG','PNG','GIF', 'webp', 'WEBP', 'jfif', 'heic', 'JFIF', 'HEIC'] : image_info['format'];
        var size = empty(image_info) ? 20 : image_info['size'];

        if(file_list.length < 1)
        {// 파일 업로드 취소
        }else
        {// 파일 업로드
            // 로딩
            spinnerOn();
            
            var file_info_list = [];

            for(var i = 0; i < file_list.length; i++)
            {   // 파일 순회
                var file = e.target.files[i];
                
                if(fileValidation(file, size, format))
                {// 파일 포맷검사

                    // 객체가 없는 경우 생성 (자동화)
                    if(empty(g_image_list[image_key]))
                    {
                        g_image_list[image_key] = [];
                    }
                    // 전역 이미지 관리 변수에 file 넣기
                    g_image_list[image_key].push(file);

                    imageInfo(file, function(file_info)
                    {// 이미지 정보 얻기
                        sync_count++;
    
                        // [재정의] 수정과 추가를 구분하기 위해 
                        file_info['idx'] = null;
                        file_info['ref_key'] = image_key;
                        file_info['o_name'] = file_info['name'];
                        file_info['path'] = file_info['url'];
                        file_info['value'] = file_info['value'];
                        file_info['hit'] = 0;
                        file_info['order_num'] = file_info['order_num'];
                        
                        file_info_list.push(file_info);

                        if(type === 'bg')
                        {// 배경화면 세팅인 경우
                            var bg_cont = $(e.currentTarget).closest('.image-bg_cont');
                            var canvas_bg = bg_cont.data('canvas_bg');

                            bg_cont.addClass('active');
                            var tag_name = bg_cont.prop('tagName').toLowerCase();

                            if(tag_name === "img")
                            {// 이미지인 경우
                                bg_cont.find(canvas_bg).attr('src', file_info['path']);
                            }else
                            {// 그 외
                                bg_cont.find(canvas_bg).css('background-image', "url('" + file_info['path'] + "')");
                            }
                        }

                        // 모든 파일 로딩 완료
                        if(sync_count == e.target.files.length){
                            // 썸네일 붙이기
                            
                            if(empty(callback) === false)
                            {// 콜백함수가 있는 경우 실행
                                callback(file_info_list);
                            }
                            spinnerOff();                            
                        }
                    });
                }else
                {// 유효성검사 실패
                    spinnerOff();
                    // callback(false);
                }
            }
        }
    },
    resetBG : function (e, callback) 
    {// 배경 세팅 초기화 (2020.08.05 / By.Chungwon)
        var edata = getEventData(e, 'image-bg_cont');
        var canvas_bg = edata['parent'].data('canvas_bg');
        var type = edata['ref_key'];
        
        edata['parent'].removeClass("active");
        $(canvas_bg).css('background-image', "");
        
        if(empty(edata['idx']) === false)
        {// DB에 존재하는 이미지인 경우
            g_image_list['delete_file_idx_list'].push(edata['idx']);
        }else
        {// 프론트엔드에서만 존재하는 이미지인 경우 file 데이터 제거하기

            // 객체가 없는 경우 생성 (자동화)
            if(empty(g_image_list[type]))
            {
                g_image_list[type] = [];
            }

            var temp = Array.from(g_image_list[type]);
            deleteListFromValue2(temp, "name", edata['o_name']);
            g_image_list[type] = temp;    
        }
        // DB 삭제
        // if(confirm('해당 파일을 정말로 삭제하시겠습니까?')){
        //     sendAPI("/userCommon", "deleteFile", { file_idx : file_idx, championship_idx : g_championship_idx }, function(res){
        //         edata['parent'].remove();
        //     });
        // }

        // 엘리먼트 삭제
        // edata['parent'].remove();
    },
    getBGUrl : function (jquery_el)
    {// 배경 URL 가져오기
        return $(jquery_el).css('background-image').split(/"/)[1];
    },
    zoom : function (params)
    {// 이미지 돋보기 (2020.09.02 / By.Chungwon)
        var img = params['jquery_element'];
        var width = params['width'];
        var height = params['height'];

        var tag_name = img.prop('tagName').toLowerCase();

        if(empty(img)){
            alert("[zoom] 이미지가 누락됐습니다.");
            return;

        }else if(tag_name !== 'img'){
            var bg = FITSOFT['IMAGE']['getBGUrl'](img);
            $('body').append("<img id='temp_zoom'>");
            img = $('#temp_zoom');
            img.attr('src', bg);
        }
        var canS = document.createElement('canvas'),
            can = document.createElement('canvas'),
            ctxS = canS.getContext('2d'),
            ctx = can.getContext('2d'),
            id = ctx.createImageData(240,240),
            de = document.documentElement;
        can.className = 'zoom';
        can.width = can.height = 240;
        canS.width = img.width;
        canS.height = img.height;
        img.parentElement.insertBefore(can,img.nextSibling);
        ctxS.drawImage(img,0,0);
        img.onmousemove = function(e){
            var idS=ctxS.getImageData(
                e.clientX-e.target.offsetLeft+(window.pageXOffset||de.scrollLeft)-20,
                e.clientY-e.target.offsetTop+(window.pageYOffset||de.scrollTop)-20,
                40,40);
            for (var y=0;y<240;y++)
                for (var x=0;x<240;x++)
                    for (var i=0;i<4;i++)
                        id.data[(240*y+x)*4+i] = idS.data[(40*~~(y/6)+~~(x/6))*4+i];
            ctx.putImageData(id,0,0);
        }

        return StringFormat("\
            <div class='position-fixed popup-item item-data {1}' title='{7}' style='background-color:{2}; left: {3}px; top:{4}px; z-index:{8};' {0}>\
                <div class='list-popup_image' style='width:{5}px; height:{6}px; overflow: hidden;'>\
                </div>\
                <div class='close-cont'>\
                    <span class='cursor-pointer btn-delete2'>\
                        오늘 하루 보지 않기\
                    </span>\
                    <span class='cursor-pointer btn-delete1'>\
                        닫기\
                    </span>\
                </div>\
            </div>\
            ",
            getlistToDataStr(['idx', 'render_type'], param),
            param['device_type'], // 디바이스 종류 (PC, Mobile, 전체)
            empty(param['bg_color']) ? "#fff" : param['bg_color'],
            param['location_x'],
            param['location_y'],
            param['size_x'],
            param['size_y'],
            param['title'],
            param['z_index']
        );
    },
}
/* 이미지 끝 */



/**************************************************** 유틸리티 *********************************************/
function multiDataController(api_name, action, item)
{// 데이터 관리 컨테이너 함수 (2021.06.18 / By.Chungwon)
// main_idx 필수!!!!!!!!!!!!
/***** 초기화 *****/
    if(empty(api_name))
    {
        alert("not find api_name in getlistToDataStr (main_idx)");
        return;
    }
    if(empty(g_multi_list[api_name])) 
    {// 첫 입력인 경우 해당 api_name으로 배열 생성 
        g_multi_list[api_name] = { 
            origin : [], 
            insert : [], 
            delete : [] 
        }; 
    }
/***** 초기화 끝 *****/

/***** 데이터 파싱 *****/
    if(action === "delete")
    {// 액션이 delete인 경우에는 main_idx만 push합니다.
        if(empty(item['main_idx']))
        {
            return;
        }
        item = item['main_idx'];
    }
    else
    {// main_idx가 있는 경우에는 origin으로 넣기 (서버에 존재하는 데이터입니다.)

        // action의 기본값은 insert

        if(empty(item['main_idx']) === false)
        {
            action = "origin";
        }
    }

/***** 데이터 파싱 끝 *****/

    g_multi_list[api_name][action].push(item);
}
function setMultiParams(multi_list)
{// 3차원 리스트 등록 (2021.09.06 / By.Chungwon)
    /*************************** insert 된 값 중, 삭제된 값 제외하기 ***************************/ 
    for(var key in multi_list)
    {
        // 추가된 전체 데이터 목록 (삭제 반영 X)
        var insert_list = multi_list[key]['insert'];
        // 화면에 추가된 데이터 목록 (삭제 반영 O)
        var item_list = $("#list-" + key).find('.item-data');
        // 실제로 전송할 idx 목록
        var real_item_list = [];

        for(var i = 0; i < item_list.length; i++)
        {// 화면에 추가된 데이터
            var item = $(item_list[i]).data();

            for(var j = 0; j < insert_list.length; j++)
            {// 등록 아이템
                var insert_item = insert_list[j];

                if(item['idx'] === insert_item['idx'])
                {
                    real_item_list.push(insert_item);
                    break;
                }
            }                    
        }
        
        
        // for(var i = 0; i < item_list.length; i++){
        // }
    }
    multi_list[key]['insert'] = real_item_list;
    multi_list[key]['origin'] = [];
    /*************************** 3차원 리스트 등록 ***************************/ 

    // params.append("multi_list", JSON.stringify(g_multi_list));
    return multi_list;
}
function setEventBinding($canvas, handler)
{// 이벤트 바인딩 (2021.06.26 / By.Chungwon)
    var method_handler = empty(handler) ? staticMethodHandler : handler;

    $canvas.not('.bind').each(function(index, item){
        var method_event = $(item).data('method_event');
        
        $(item).on(method_event, method_handler);
        $(item).addClass('bind');
    });
}
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
function searchDatepicker(container)
{// datepicker(검색)
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
        ignoreReadonly : true,
    });
}
function searchDateTimepicker(container, params)
{// datepicker(검색)
    var time_format = empty(params['time_format']) ? "HH:mm:ss" : params['time_format'];

    $(container).datetimepicker({
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
        ignoreReadonly : true,        
        timeFormat:time_format,
        controlType:'select',
        oneLine:true,
    });
}

// 에디터(썸머노트) 사진 삽입 이벤트 연동 (2020.12.03 / By.Chungwon)
function createEditorForContent(param){
    var selecter = param['selecter'];
    var file_path = param['file_path'];
    var target_table = param['target_table'];

    if(empty(target_table)){
        alert("g_table이 존재하지 않습니다.");
        return;
    }
    $(selecter).summernote({
        placeholder: '',
        tabsize: 2,
        height: 240,
        lang: "ko-KR",
        callbacks: {
            onImageUpload: function(files) {    // 이미지 업로드 콜백
                var formData = new FormData();

                for (var i = 0; i < files.length; i++) {
                    formData.append('editor[]', files[i]);
                }
                formData.append('type', "image");
                formData.append('ref_table', target_table);
                formData.append('ref_idx', "0");
                
                sendAPI("/file", "insertEditTempFile", formData, function(res)
                {
                    var file_info_list = res.file_info_list;

                    if(empty(file_info_list) === false)
                    {// 파일 정보가 있다면
                        for(var i = 0; i < file_info_list.length; i++){
                            var file_info = file_info_list[i];

                            // 기존 이미지 목록에 넣기
                            g_image_list['editor_upload_image_idx'].push(file_info['idx']);
                            $(selecter).summernote('insertImage', file_path + file_info['path'], file_info['idx']);
                            // $(selecter).summernote('insertImage', "<?=$PREFIX['FILE']?>" + file_info['path'], file_info['idx']);
                        }
                    }
                });
            }
        }
    });
}

function joinFormValidity(user_info){

    if(user_info['password'] !== user_info['password_chk']){
        alert("비밀번호가 서로 다릅니다.");
        return false;
    }
    // 이메일 확인
    if(user_info['email'].includes('@') == false){
        alert("올바른 이메일 형식을 작성해주세요.");
        $("[data-key=email]").focus();
        return false;
    }
    // 연락처를 한번에 입력받는 경우
    var phone_number = user_info['tel'].toString().length;
    if(phone_number > 12 || phone_number < 10 ){
        alert("올바른 핸드폰 번호를 작성해주세요.");
        return false;
    }

    // 약관 필터링
    var total_count = $('.clause').length;

    if(total_count > 0)
    {// 약관이 있는 경우
        var count = $(".clause:checked:not(.clause_all)").length;

        if(total_count !== count){
            alert("약관에 동의해주세요.");
            return false;
        }
    }
}
function formValidity(prefix, skip_key_list, data_list)
{// 폼 데이터 유효성 검사 (2021.08.30 / By.Chungwon)
    
    var params = autoGetItem(prefix);
    data_list = empty(data_list) ? getFormData(params) : data_list;

    var validity_list = $("[data-" + prefix + "]");
    for(var i = 0; i < validity_list.length; i++)
    {
        var validity = $(validity_list[i]);
        var key = validity.data(prefix);

        if(empty(data_list[key]) && skip_key_list.indexOf(key) == -1)
        {// 값이 없고 스킵 목록에도 없는 경우, 경고 출력 후 리턴
            var alert_msg = empty(validity.attr('title')) ? validity.attr('placeholder') : validity.attr('title');
            alert_msg = empty(alert_msg) ? key : alert_msg;

            alert(alert_msg); 
            
            validity.focus(); 
            return false;
        }
    }
    return true;
}
function setEditAndFile(params, datas)
{// 에디터 및 파일처리 (2021.07.17 / By.Chungwon)
    var ref_table = empty(datas) ? "" : datas['ref_table'];
    
    /*************************** 에디터 이미지 처리 ***************************/ 
    if($('.editor-content').length > 0)
    {// 에디터가 생성된 경우에만 호출.
        // 현재 에디터에 등록된 이미지 가져오기
        var current_editor_image_idx = $('.editor-content').next('.note-editor').find('.note-editable').find('img');        
        current_editor_image_idx = getSummerNoteImgList(current_editor_image_idx);

        // 에디터에서 삭제된 이미지 idx 가져오기
        var delete_image_idx_list = compareArrayResultNotDuplicate(g_image_list['editor_upload_image_idx'], current_editor_image_idx);
        for(var i = 0; i < delete_image_idx_list.length; i++){
            g_image_list['delete_file_idx_list'].push(delete_image_idx_list[i]);
        }

        /*************************** 에디터 내용 등록 ***************************/ 
        params.delete('content');
        params.append('content', htmlEscape($('.editor-content').summernote('code')));
    }            

    /*************************** 파일첨부 등록 및 삭제 ***************************/ 
    // 삭제된 파일과 실제 전송 데이터의 동기화 작업
    for(var key in g_image_list)
    {// 이미지 수에 따른 동적처리
        var value = g_image_list[key];

        if(key === 'delete_file_idx_list')
        {// 파일 삭제 idx인 경우 (문자열로 묶음) - 삭제
            params.append(key, value.join(','));                
        }else
        {// 파일 객체인 경우 (동적 처리) - 등록
            key = key + "[]";
            params.delete(key);

            for(var i = 0; i < value.length; i++)
            {// 파라미터 insert
                params.append(key, value[i]);
            }
        }
    }
    // 파일 객체 데이터 추가 (value, order_num)
    var file_update_list = [];
    var img_info_list = $("#cont-image_detail .item-data");
    // 파일 객체 데이터 정보
    for(var i = 0; i < img_info_list.length; i++){
        var $file = $(img_info_list[i]);
        var item = $file.data();

        item['type'] = "image";
        item['ref_table'] = ref_table;
        item['value'] = $file.find("[data-name='value']").val();
        item['order_num'] = $file.find("[data-name='order_num']").val();
        
        file_update_list.push(item);
    }
    params.append("file_update_list", JSON.stringify(file_update_list));

    return params;
}
function fileChangeEvent(e, params) 
{// 파일 객체 변경 이벤트 (2021.07.17 / By.Chungwon)
    var success_callback = empty(params['success_callback']) ? function(){} : params['success_callback'];
    var fail_callback = empty(params['fail_callback']) ? function(){} : params['fail_callback'];
    var format = empty(params['format']) ? ['jpg','jpeg','png','gif','bmp','BMP','JPG','JPEG','PNG','GIF', 'webp', 'WEBP', 'jfif', 'heic', 'JFIF', 'HEIC'] : params['format'];
    var size = empty(params['size']) ? 20 : params['size'];

    var file_list = e.target.files;
    var image_key = $(e.currentTarget).data('file_key');

    /********** 유효성 검사 **********/
    if(file_list.length < 1)
    {// 파일 업로드 취소 
        return; 
    }
    if(empty(g_image_list))
    {// 필수 값 누락
        alert("g_image_list is undefined\n관리자에게 문의해주세요.");
    }
    if(empty(image_key))
    {// 필수 값 누락
        alert("image_key is undefined\n관리자에게 문의해주세요.");
    }
    /********** 유효성 검사 끝 **********/
    

    
    /********** 데이터 파싱 (필수 값 생성) **********/
    var sync_count = 0; // 동기화 카운팅 변수
    var file_info_list = []; // 파싱된 파일 정보를 담을 배열

    if(empty(g_image_list[image_key]))
    {// 객체가 없는 경우 생성 (자동화) 
        g_image_list[image_key] = []; 
    }
    /********** 데이터 파싱 (필수 값 생성) 끝 **********/

    
    // 로딩
    spinnerOn();
    
    $(file_list).each(function(i, file){
        if(fileValidation(file, size, format) === false)
        {// 파일 포맷검사 실패
            spinnerOff();
            fail_callback(file);
            return;
        }

        
        imageInfo(file, function(file_info)
        {// 이미지 정보 얻기
            sync_count++;

            // [재정의] 수정과 추가를 구분하기 위해 
            file_info['idx'] = null;
            file_info['ref_key'] = image_key;
            file_info['o_name'] = file_info['name'];
            file_info['path'] = file_info['url'];
            file_info['value'] = file_info['value'];
            file_info['hit'] = 0;
            file_info['order_num'] = file_info['order_num'];
            
            // 리턴 변수에 file 넣기
            file_info_list.push(file_info);

            // 전역 이미지 관리 변수에 file 넣기
            g_image_list[image_key].push(file);
            
            if(sync_count == file_list.length)
            {// 모든 파일 로딩 완료

                success_callback(file_info_list);                
                spinnerOff();                            
            }
        });
    });
}

function validity(validity_list)
{
    validity_list = empty(validity_list) ? $("[data-upload_key]") : validity_list;
    var skip_key_list = []; // 생략 가능한 값 목록 (입력 불가능한 초기값이 있는 경우)
    
    for(var i = 0; i < validity_list.length; i++)
    {
        var validity = $(validity_list[i]);
        var key = validity.data('upload_key');

        if(empty(glist[key]) && skip_key_list.indexOf(key) == -1)
        {// 값이 없고 스킵 목록에도 없는 경우, 경고 출력 후 리턴
            var alert_msg = empty(validity.attr('title')) ? validity.attr('placeholder') : validity.attr('title');
            alert_msg = empty(alert_msg) ? key : alert_msg;

            alert(alert_msg); 
            
            validity.focus(); 
            return false;
        }
    }
    return true;
}

/**************************************************** 유틸리티 끝 *********************************************/