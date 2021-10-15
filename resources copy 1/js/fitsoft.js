// INIT
var PAGE_PC_MIN_WIDTH = 768;
var GOOGLE_MAPS_PLATFORM = 'AIzaSyBtKehIy1ykLpyX7nARuFiTn4bWL-nneiQ';
var is_mobile = window.outerWidth < PAGE_PC_MIN_WIDTH ? true : false;
var g_site_name = "fdcompany";

$(function(){
    $(".pageTop").on('click', function(){
        customFocus($("html"), 500, 0);
    });
});
$(window).resize( function() {
    is_mobile = window.outerWidth < PAGE_PC_MIN_WIDTH ? true : false;
});

/*
    데이터가 비어있는지 확인
    2020.04.02 / By.서청원
*/
function empty(obj){
    if( obj === '' || obj === null || obj === undefined || 
        ( obj != null && typeof obj == "object" && !Object.keys(obj).length ) ){ 
        return true;
    }

    return false;
}

/*
    URL에 파라미터 붙이기
    2020.04.06 / By.서청원
*/
function setParamsToUrl(url, params){

    var data = "";
    if(empty(params) === false){

        for(var key in params){
            data += key + '=' + params[key] + '&';
        }
        data = data.indexOf('&') != -1 ?  data.slice(0, -1) : data;
    }
    url = url.indexOf('?') != -1 ? url : url + "?";

    // 마지막 & 삭제
    return url + data;
};

/*
    Ajax 기본 설정 셋팅
    2020.04.02 / By.서청원
*/
// 스피너 켜기 (2020.06.25 / By.Chungwon)
function spinnerOn(ajax_key){

    if(empty(ajax_key) === false)
    {// ajax 스피너 동작
        if($(".spinner.ajax[data-ajax_key='" + ajax_key + "']").length > 0)
        {// ajax 스피너가 있는 경우
            $(".spinner.ajax[data-ajax_key='" + ajax_key + "']").addClass('active');
        }
    }else
    {// 전역 스피너 동작
        if($('spinner:not(.ajax)').length < 1)
        {// 스피너 객체가 없는 경우 생성
            $('body').append("<div class='spinner'></div>");
        }
        // 스피너 동작
        $('.spinner:not(.ajax)').addClass('active');
    }
}
// 스피너 끄기 (2020.04.10 / By.Chungwon)
function spinnerOff(ajax_key){
    if(empty(ajax_key) === false)
    {// ajax 스피너 동작 중지
        if($(".spinner.ajax[data-ajax_key='" + ajax_key + "']").length > 0)
        {// ajax 스피너가 있는 경우
            $(".spinner.ajax[data-ajax_key='" + ajax_key + "']").removeClass('active');
        }
    }else
    {// 전역 스피너 동작 중지
        if($('spinner:not(.ajax)').length < 1)
        {// 스피너 객체가 없는 경우 생성
            $('.spinner:not(.ajax)').remove();
        }
        // 스피너 동작
        $('.spinner:not(.ajax)').removeClass('active');
    }
}
$.ajaxSetup({
    error : function(response){
        var default_text = '서버에서 에러가 발생했습니다.\n관리자에게 문의바랍니다.';
        var error_msg = response.responseText;
        error_msg = empty(error_msg) ? default_text : JSON.parse(error_msg).result;
            
        alert(error_msg);
    },
    beforeSend: function () { },
    complete: function () {  }
});


/*
    Ajax 전송
    2020.04.06 / By.서청원
*/
function ajaxSend(url, method, param, s, f, c){

    method = method.toLowerCase();
    url = method === 'post' ? url : setParamsToUrl(url, param);
        
    var option = {
        url : url,
        type : method
    };
    option['success'] = empty(s) ? null : s;

    if(!empty(f)){
        option['error'] = f;    
    }
    if(!empty(c)){
        option['complete'] = c;
    }
        
    if(method === 'post'){
        option['processData'] = false;
        option['contentType'] = false;
        option['data'] = param;
    }
    $.ajax(option);
}

function FitSoftApiSend(url, action, param, success, fail, complete){

    var formData = new FormData();
    
    if(param instanceof FormData){
        formData = param;
    }else{
        for (var key in param) {
            formData.append(key, param[key]);
        }
    }
    formData.append('action', action);

    ajaxSend(url, "post", formData, function(res){ 
        var data = empty(res) ? null : JSON.parse(res)
        success(data); 
    }, fail, complete);
}

function sendAPI(api, action, param, success, fail, complete){
    var context_path = "";
    var url = context_path + "/api" + api + ".php";

    if(api.indexOf("http://") !== -1 || api.indexOf("https://") !== -1)
    {// 외부 API 인 경우,
        url = api;
    }

    FitSoftApiSend(url, action, param, success, fail, complete);
}

// post 전송
function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
// String Format
function StringFormat(){
    var theString = arguments[0];
    
    for (var i = 1; i < arguments.length; i++) {
    var regEx = new RegExp("\\{" + (i - 1) + "\\}", "gm");
    theString = theString.replace(regEx, arguments[i]);
    }
    
    return theString;
}

/*
    클립 보드에 값 저장
    2020.04.06 / By.서청원
*/
function clipboardCopy(copy){
    $(copy).nextAll('input').select();
    document.execCommand('copy');
    
    autoAlert("주소를 클립보드에 복사했습니다.", 3000, 55);
}
/*
    문자열이 JSON인지 확인
    2020.05.09 / By.서청원
*/
function IsJsonString(str) {
    try {
        var json = JSON.parse(str);
        return (typeof json === 'object');

    }   catch (e) {
        return false;
    }
}
// 문자 사이 값 추출하기
function getStrAmong(target, start, end){
    // 비교 문자열 위치 검색
    target = decodeURIComponent(target);
    var startIndex = target.indexOf(start);

    // 문자열 검색
    if(startIndex != -1){
        // 전체 문자열에서 비교 문자열 앞 문자까지 삭제
        var surplus = target.substr(startIndex, target.length);
        // 끝 문자열 위치 검색
        var endPoint = surplus.indexOf(end);

        // 끝 문자열 검색
        if(endPoint != -1){
            // 존재할 경우 끝 문자열까지만 삭제
            return surplus.substr(start.length, endPoint - start.length);
        }else{
            // 존재하지 않을 경우 끝까지 삭제
            return surplus.substr(start.length, target.length);
        }
    }

    return false;
}

/*
    2차원 배열 1차원으로 변경 (2020.02.13 / By.Chungwon)
    * 중복 시 뒤의 인덱스로 덮어씀
*/
function setArrayDimension(params){
    var params_dimension1 = {};

    for(var key in params){
        if(typeof(params[key]) !== 'object'){
            params_dimension1[key] = params[key];
            continue;
        }

        params_dimension1 = $.extend(params_dimension1, params[key]);
    }
    
    return params_dimension1;
}
/*
    data-[키]에 자동으로 세팅하기 (2020.04.07 / By.Chungwon)
    (파라미터 info는 해시맵 배열입니다.)
*/
function autoSetItem(info, prefix){
    prefix = empty(prefix) ? 'key' : prefix;

    for(var column in info){
        var element = $("[data-" + prefix + "=" + column + "]");
        var value = info[column];
        var option = element.data('option');
        var no_binding = element.data('no_binding');

        if(empty(element) || element.length === 0){
            continue;
        }

        if(option === 'number'){
            // $(element).data('is_comma');
            vaildateNumber(element);
        }
        if(no_binding === true)
        {
            continue;   
        }
        var tagName = element.prop('tagName').toLowerCase();

        if(element.data('ispic') == '1'){
            element.addClass('basic-bg');
            element.css('background-image', "url(" + value + ")");

        }else if(tagName === 'select'){       
            // select 인 경우
            element = $("[data-" + prefix + "='" + column + "'] > option[value='" + value + "']");
            element.attr('selected', 'selected');

        }else if(tagName === 'input'){
            var type = element.attr('type');
            // input 인 경우
            if(type === 'checkbox'){       
                // checkbox 인 경우
                var value_list = value.split(',');

                if(value_list.length > 1){
                    // N개
                    for(var i = 0; i < value_list.length; i++){
                        value = value_list[i];

                        var el_checked = $("[data-" + prefix + "='" + column + "'][value='" + value + "']");
                        $(el_checked).prop('checked', true);
                    }
                }else{
                    // 1개
                    // $("[data-key='" + column + "']").prop('checked', false);

                    var el_checked = $("[data-" + prefix + "='" + column + "'][value='" + value + "']");                    
                    $(el_checked).prop('checked', true);
                }

            }else if(type === 'radio'){
                // radio인 경우
                element.filter("[value='" + value + "']").prop('checked', true);

            }else{
                element.val(value);
            }
        }else if(tagName === 'textarea'){
            // textarea인 경우
            element.val(value);
        }else{
            // 그 외 텍스트 span, p, div, etc
            for(var i = 0; i < element.length; i++){
                var el = $(element[i]);

                if(el.data('is_active')){
                    // active 클래스만 붙일 경우 (커스텀디자인)
                    if(el.text() == value || el.data('value') == value){
                        el.addClass('active');
                    }
                    
                }else if(el.data('is_multi_active')){
                    // 다중인 경우
                    var val = el.data('value');
                    var text = empty(val) ? el.text() : val;
                    
                    if(value.indexOf(text) != -1){
                        el.addClass('active');
                    }
                }else if(el.data('is_null')){
                    // null 상태에 따라 엘리먼트가 달라질 경우
                    var is_null = el.data('value') === null;

                    if(is_null)
                    {// 실제 데이터가 null인 경우 보여지는 엘리먼트입니다.
                        if(value === null || value === "null" || value === "" || value === "0")
                        {// 실제 데이터가 null 인 경우
                            el.addClass('active');
                        }else
                        {// 실제 데이터가 null이 아닌 경우
                            el.css('display', 'none');
                        }
                    }else
                    {// 실제 데이터가 null이 아닌 경우 보여지는 엘리먼트입니다.
                        if(value === null || value === "null" || value === "" || value === "0")
                        {// 실제 데이터가 null 인 경우
                            el.css('display', 'none');
                        }else
                        {// 실제 데이터가 null이 아닌 경우
                            el.addClass('active');

                            value = empty(value) ? "" : value;
                            el.html(value);
                        }
                    }
                }
                else{
                    
                    // text만 변경할 경우
                    value = empty(value) ? "" : value;
                    el.html(value);
                }
            }
        }
    }
}
/*
    정렬 값 자동으로 세팅하기 (2020.05.09 / By.Chungwon)
*/
function autoSetSort(list, prefix){
    var prefix = empty(prefix) ? "key" : prefix;
    var temp_sort_list = list.split(',');
    var sort_list = {};

    for(var i = 0; i < temp_sort_list.length; i++){
        var item = temp_sort_list[i].split(' ');
        var key = item[0];
        var value = item[1];

        sort_list[key] = value;
    }
        
    var sort_el_list = $('.sort-item');
    
    for(var i = 0; i < sort_el_list.length; i++){
        var item = $(sort_el_list[i]);
        var key = item.data(prefix);
        var order = sort_list[key];
        order = empty(order) ? "" : order;

        item.addClass('sort-' + order);
        item.data('sort_order', order);
    }
}
/*
    파라미터 list에 자동으로 input 값 넣기 (2020.04.07 / By.Chungwon)
    parent는 Jquery 객체. form이 여러개인 경우 사용됌.
*/
function autoGetItem(prefix, parent){
    prefix = empty(prefix) ? 'key' : prefix;
    
    var form = new FormData();
    var element_list = empty(parent) ? $("[data-" + prefix + "]") : parent.find("[data-" + prefix + "]");

    for(var i = 0; i < element_list.length; i++){
        var target = $(element_list[i]);
        var key = target.data(prefix);
        var option = target.data('option');
        var tag_name = target.prop('tagName').toUpperCase();

        if(option === 'number'){
            // 숫자인 경우, 콤마 제거
            target.val(deleteComa(target.val()));
        }
        if(tag_name === "INPUT"){
            var type = target.attr('type');
            type = empty(type) ? 'TEXT' : type.toUpperCase();

            if(type === 'CHECKBOX'){
                // 체크박스나 라디오 박스 인 경우
                if(target.prop('checked')){
                    form.append(key, target.val());
                }

            }else if(type === 'RADIO'){
                // radio인 경우
                if(target.prop('checked')){
                    form.append(key, target.val());
                }

            }else if(type === 'FILE'){
                var files = target[0].files;

                if(typeof(files) === 'object'){
                    // 2차원 해쉬맵 배열
                    if(empty(files['length']) === false){
                        // 3차원 해쉬맵 배열
                        for (var j = 0; j < files.length; j++) {
                            if(empty(files[j]['length'])){
                                form.append(key + '[]', files[j]);
                            }
                        }
                    }else{
                        form.append(key, files);
                    }
                }else{
                    // 해쉬맵 배열
                    form.append(key, files);
                }
            }else{
                // input 또는 select 인 경우
                form.append(key, target.val());
            }
        }else if(tag_name === 'SELECT'){
            // SELECT인 경우
            var is_list = target.data('islist') === true ? true : false;
            
            if(is_list){
                // 다중선택 인 경우 매핑 자식에 data-select_list 추가
                var item_list = $("[data-select_list='" + key + "']");

                var temp_list = [];
                for(var j = 0; j < item_list.length; j++){
                    var item = $(item_list[j]);
                    temp_list.push(item.data('select_value'));
                }
                form.append(key, temp_list.join(','));
            }else{
                // 단일 선택인 경우
                form.append(key, target.val());

            }
        }else if(tag_name === "TEXTAREA"){
            // textarea 인 경우
            form.append(key, target.val());
        }else
        {
            // span 이나 p 인 경우
            form.append(key, target.text());
        }
    }

    return form;
}
/*
    이벤트 자동 연동 후 list에 변경된 값 세팅 (2020.06.10 / By.Chungwon)
*/
function autoSetEvent(after_callback, prefix, before_callback){
    prefix = empty(prefix) ? 'key' : prefix;

    var el_list = $('[data-event_type]');

    for(var i = 0; i < el_list.length; i++){
        var target = $(el_list[i]);
        var event_type = target.data('event_type');

        target.on(event_type, function(e){
            if(empty(before_callback) === false){
                before_callback(e);
            }

            var target = $(e.currentTarget);
            var tag_name = target.prop('tagName').toLowerCase();

            var key = target.data(prefix);
            var value = "";

            if(tag_name === 'input'){
                var type = target.attr('type').toLowerCase();

                if(type === 'checkbox'){
                    var value_list = [];
                    var checkbox_list = $("[data-" + prefix + "='" + key + "']:checked");

                    for(var j = 0; j < checkbox_list.length; j++){
                        var box = $(checkbox_list[j]);
                        var temp_val = box.val();

                        value_list.push(temp_val);
                    }
                    value = value_list.join(',');

                }else{
                    value = target.val();
                }
            }else if(tag_name === "select"){
                value = target.val();

            }
            else if(tag_name === 'form'){
                var target_list = target.find('.event-search_keyword');

                for(var j = 0; j < target_list.length; j++){
                    var temp_target = $(target_list[j]);
                    value = temp_target.val();
                    key = temp_target.data(prefix);

                    after_callback({ key : key, value : value });
                }
                return;

            }else{
                if(target.hasClass('sort-item')){
                    // 정렬 컬럼인 경우
                    if(target.data('sort_order') === ''){
                        target.data('sort_order', 'desc');
                        target.addClass('sort-desc');
                        target.removeClass('sort-');
                    
                    }else if(target.data('sort_order') === 'desc'){
                        target.data('sort_order', 'asc');
                        target.addClass('sort-asc');
                        target.removeClass('sort-desc');
                    
                    }else if(target.data('sort_order') === 'asc'){
                        target.data('sort_order', '');
                        target.addClass('sort-');
                        target.removeClass('sort-asc');
                    }


                    var order_str = [];
                    var order_el_list = $(".sort-item");
                    
                    for(var i = 0; i < order_el_list.length; i++){
                        var el = $(order_el_list[i]);
                        var sort_type = el.data('sort_key');
                        sort_type = empty(sort_type) ? el.data(prefix) : sort_type;
                        var order = el.data('sort_order');
                        
                        if(empty(order) === false){
                            order_str.push(sort_type + " " + order);
                        }
                    }
                    key = 'sort_list';
                    value = order_str.join(',');
    
                }else if(target.data('is_multi_active')){
                    // 여러개 설정이 가능한 경우

                    var value_list = [];
                    var active_list = $("[data-key='" + key + "'].active");

                    for(var j = 0; j < active_list.length; j++){
                        var item = $(active_list[j]);
                        var val = item.data('value');
                        val = empty(val) ? item.val() : val;    

                        value_list.push(val);
                    }
                    value = value_list.join(',');

                }else{
                    // 기타
                    value = target.data('value');
                }
            }

            after_callback({ key : key, value : value });
        });
    }
}

function autoSetEvent2(params)
{/*
    이벤트 자동 연동 후 list에 변경된 값 세팅 (2021.06.05 / By.Chungwon)
    action (필수), key(필수), value / index (둘 중 하나 필수), c_name (선택)
*/

    // 클래스명 기본값 재정의
    params['c_name'] = empty(params['c_name']) ? "active" : params['c_name'];
    // 데이터 엘리먼트 목록
    var element_selector = "#list-" + params['action'] + " .item-data";

    /****************** 필터링 ******************/
    if(empty(params['action']))
    {// 액션 값이 없는 경우 이벤트 동작 불가)
        alert("액션값이 누락됐습니다.");
        return;
    }


    /****************** 인덱스로 value 추출 ******************/
    if(empty(params['value']))
    {// 값이 없는 경우
        if(empty(params['index']))
        {// 인덱스도 없는 경우 (이벤트 동작 불가)
            return;
        }
        var element = $(element_selector).eq(params['index']);
        params['value'] = element.data(params['key']);
    }

    /****************** 해당 엘리먼트에 CLASS 추가 ******************/
    // 현재 값을 기준으로 엘리먼트 선택
    var current_item = $(element_selector + "[data-" + params['key'] + "='" + params['value'] + "']");
    // 동일한 아이템 class 삭제
    $(element_selector).removeClass(params['c_name']);
    // 현재 아이템 class 삭제
    current_item.addClass(params['c_name']);
    params['index'] = current_item.index();

    /****************** 2차원 배열(g_req)에 세팅 ******************/
    // 요청 파라미터 세팅
    setRequestParam(params['action'], params['key'], params['value']);
    return params;
}
function setRequestParam(action, key, value)
{// 요청 파라미터 세팅 (2021.06.05 / By.Chungwon)
    if(empty(g_req))
    {
        alert("g_req가 없습니다.");
    }
    g_req[action][key] = value;
    // 해쉬맵과도 연동
    changeHash(document.location.pathname, { params : JSON.stringify(g_req) });
}

function getFormData(form){
    var data_list = {};

    var formDataEntries = form.entries(), formDataEntry = formDataEntries.next(), pair;

    while (!formDataEntry.done) {
        pair = formDataEntry.value;
        var key = pair[0];
        var value = pair[1];

        if(typeof(value) === 'object'){
            // 1차원 배열
            if(empty(data_list[key])){
                data_list[key] = [];
            }
            data_list[key].push(value);

        }else{
            data_list[key] = value;
        }

        formDataEntry = formDataEntries.next();
    }
    return data_list;
}
/*
    해쉬 URL 변경하기 (2020.02.13 / By.Chungwon)
    
    URL - 이동 주소
    params_dimension1 - 1차원
    params_dimension2 - 2차원
*/
// function changeHash(url, params){
//     console.log(params);
//     history.pushState(params, null, url);
// }
function changeHash(url, params){
    // 파라미터를 포함한 URL 생성
    var new_url = setParamsToUrl(url, params);

    history.pushState(params, null, new_url);
}

// 해쉬맵 배열 합치기 (중복인 경우 arr2으로 대체)
function addHashMap(arr1, arr2){
    for(var key in arr2){
        arr1[key] = arr2[key];
    }
    return arr1;
}
/*
    이미지 정보 얻기 
    (2020.04.09 / By.Chungwon)    
*/
function imageInfo(file, func){
    if(file === undefined){
        alert("이미지만 등록이 가능합니다.");
        return false;
    }
    var reader = new FileReader();
    var image  = new Image();

    reader.readAsDataURL(file);  
    reader.onload = function(_file) {
        image.src = _file.target.result;              // url.createObjectURL(file);
        image.onload = function() {
            var info = {};
            info['width'] = this.width,
            info['height'] = this.height,
            info['type'] = file.type,                           // ext only: // file.type.split('/')[1],
            info['ext'] = (file['name'].split('.')).pop(),                           
            info['name'] = file.name,
            info['size'] = ~~(file.size/1024) +'KB';
            info['url'] = this.src;
            info['file'] = this;

            func(info);
        };
        image.onerror = function() {
            func(null);
        };      
    };
}
/*
    파일 크기 검사 
    (2020.04.09 / By.Chungwon)    
*/
function fileValidation(info, size, ext_list){
    var maxSize = size * 1024 * 1024;
       
    if(info.size > maxSize){
        alert(size + 'MB이하의 파일을 등록해주세요.');
        return false;
    }

    if(empty(ext_list) === false){
        for(var i = 0; i < ext_list.length; i++)
        {// 허용가능한 확장자 목록
            // 온점 (.) 제거하기
            ext_list[i] = ext_list[i].replaceAll('.', '');
            // 공백 제거하기
            ext_list[i] = ext_list[i].trim();
            // 소문자로 변경하기
            ext_list[i] = ext_list[i].toLowerCase();
        }

        var ext = info['name'].split('.');
        ext = ext.pop();

        if(ext_list.indexOf(ext.toLowerCase()) === -1){
            alert(info['name'] + "파일의 확장자 ['" + ext + "']는 등록할 수 없습니다.");
            return false;
        }       
    }
    return true;
}
/*
    숫자 입력 유효성 검사
    data-is_comma가 true인 경우, 세번째 값마다 콤마 추가
    (2020.04.10 / By.Chungwon)    
*/
function vaildateNumber(target){
    var value = target.value;
    value = empty(value) ? target.innerHTML : value;
    value = String(value).replace(/[^\d]+/g, '');

    var is_comma = $(target).data('is_comma');
    if(empty(is_comma) === false && is_comma){
        value = value.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }

    if(empty(target.value)){
        target.innerHTML = value;     
    }else{
        target.value = value;
    }
}

/*
    텍스트에 콤마 제거 한 뒤 숫자 반환
    (2020.04.10 / By.Chungwon)    
*/
function deleteComa(number){
    if(empty(number)){
        return number;
    }
    return Number(number.replace(/,/g,""));
}

/*
    Input file 초기화
    (2020.04.14 / By.Chungwon)
*/
function deleteInputFile(input_file){
    input_file.value = "";
    
    if(!/safari/i.test(navigator.userAgent)){
        input_file.type = ''
        input_file.type = 'file'
    }
    return true;
}

/*
    동적 이벤트 바인딩 - 자주 사용되는 데이터 가져오기
    타겟, 부모, data 집합
    (2020.05.13 / By.Chungwon)
*/
function getEventData(event, parent_class){

    var target = $(event.currentTarget);
    var parent = empty(parent_class) ? target : target.closest('.' + parent_class);
    var data = parent.data();

    var result = {
        target : target,
        parent : parent,
    };

    for(var key in data){
        var value = data[key];
        
        result[key] = value;
    }
    
    return result;
}
/*
    파일 클릭 시 폼과 이벤트 자동 연동
    (2020.05.13 / By.Chungwon)
*/
function setAutoFileEvent(e){
    var selector = $(e.currentTarget).data('file_selector');
    $(selector).click();
}
/*
    hashmap 배열을 data-column 문자열 리스트로 가져오기
    ex. {key : name, value : 홍길동, age : 28} => data-key='name' value='홍길동' age='28'
    (2020.09.12 / By.Chungwon)
*/
function getlistToDataStr(get_list, data_list){

    var total_list = [];

    for(var key in data_list){
        var is_insert = false;

        if(get_list === null)
        {// 입력하지 않은 경우 data_list의 전체 값 삽입
            is_insert = true;
        }
        else if(get_list.indexOf(key) !== -1){
            is_insert = true;
        }

        if(is_insert)
        {
            var value = data_list[key];
            var str_item = StringFormat("data-{0}='{1}'", key, value);

            total_list.push(str_item);
        }
    }

    return total_list.join(' ');
}

// 셀렉트박스의 선택된 엘리먼트 가져오기 (2020.02.20 / By.Chungwon)
function getSelectElement(e){
    var index = e.currentTarget.selectedIndex;
    var selected = e.currentTarget.options[index];

    return selected;
}

/*
    1차원 배열, 값으로 삭제하기
    (2020.05.15 / By.Chungwon)
    반환값 X, 배열에서 자동으로 삭제됩니다.
*/
function deleteListFromValue(list, value){
    var idx = list.indexOf(value);
    if(idx != -1){
        list.splice(idx, 1);
    }
    return list;
}
/*
    2차원 배열 중 컬럼 값으로 삭제하기
    (2020.07.08 / By.Chungwon)
    반환값 X, 배열에서 자동으로 삭제됩니다.
*/
function deleteListFromValue2(list, column, value){
    for(var i = 0; i < list.length; i++){
        var item = list[i];

        if(item[column] === value){
            list.splice(i, 1);

            return list;
        }
    }
}
/*
    2차원 배열 중 선택된 컬럼의 값을 구분값을 추가하여 문자열 반환
    (2020.05.15 / By.Chungwon)
*/
function getStringFromList(list, column, division, wrap){
    var temp_list = [];
    wrap = empty(wrap) ? '' : wrap;

    for(var i = 0; i < list.length; i++){
        var item = list[i];
        temp_list.push(item[column]);
    }

    return temp_list.join(division);
}
// 배열 중복 제거
function deleteDuplicateArray(arr){
    return arr.filter(function(value, index, self){
        return self.indexOf(value) === index;
    });
}

/*
    SELECT 카테고리 변경
    (2020.06.03 / By.Chungwon)
*/
function changeSelectCategory(target, class_name){
    var deep = Number(target.data('deep'));
    var value = target.val();

    // 변경된 객체 다음 select option 리셋
    var parent_count = $(class_name).length;
    var temp_deep = deep;

    while(temp_deep++ < parent_count){
        var next_target = $(class_name + "[data-deep='" + temp_deep + "']");
        // 선택하지 않은 경우, option 초기화
        next_target.find("option:not([value=''])").remove();
    }

    // deep이 1 이고 값이 없는 경우 불러오지 않음.
    if(deep === 1 && value === ''){
        resetSelectCategory(class_name);
        // return;

    }else if(value === ''){
        var selected_option = $(class_name + "[data-deep='" + (deep - 1) + "'] option:selected");
        value = selected_option.val();

    }else{

    }
    return {
        deep : deep,
        value : value,
    };
}
/*
    SELECT 카테고리 분류 리셋
    값이 있는 경우 active 처리(?)
    (2020.06.03 / By.Chungwon)
*/
function resetSelectCategory(class_name){
    $(class_name).each(function(index, item){ 
        var option_count = $(item).find('option').length;
        if(option_count < 2){
            $(item).closest(class_name).removeClass('active');
        }else{
            $(item).closest(class_name).addClass('active');
        }
    });
}
/*
    SELECT 카테고리 정보 얻기
    (2020.06.04 / By.Chungwon)
*/
function getSelectCategory(class_name){
    var info = [];

    $(class_name).each(function(index, item){
        item = $(item);

        var deep = Number(item.data('deep'));
        var value = item.val();
        var selected_option = $(class_name + "[data-deep='" + (deep) + "'] option:selected");
        var text = selected_option.text();

        var temp = {
            deep : deep,
            value : value,
            text : text,
        };
        
        info.push(temp);
    });
    return info;
}
/*
    등록된 SELECT 카테고리 정보 얻기
    (2020.06.04 / By.Chungwon)
*/
function getSelectedCategoryItem(class_name){
    var info = [];

    $(class_name).each(function(index, item){
        item = $(item);
        info.push(item.data());
    });
    return info;
}
/*
    일자를 개월수로 변환
    (2020.06.10 / By.Chungwon)
*/
function getDateToDay(day_count){
    day_count = Number(day_count);
    return {
        year : parseInt(day_count / 365),
        month : parseInt((day_count / 30) % 12)
    };
}
/*
    오늘 날짜 구하기
    (2020.06.10 / By.Chungwon)
*/
function getDate(date, operDate){

    var today = empty(date) ? new Date() : new Date(date);
    var year = today.getFullYear();
    var month = today.getMonth() + 1;
    var day = today.getDate();
    var hour = today.getHours();
    var minute = today.getMinutes();
    var second = today.getSeconds();

    if(!empty(operDate)){
        year += empty(operDate.year) ? 0 : operDate.year;
        month += empty(operDate.month) ? 0 : operDate.month;
        day += empty(operDate.day) ? 0 : operDate.day;
        hour += empty(operDate.hour) ? 0 : operDate.year;
        minute += empty(operDate.minute) ? 0 : operDate.minute;
        second += empty(operDate.second) ? 0 : operDate.second;
    }

    month = month < 10 ? '0' + month : month;
    day = day < 10 ? '0' + day : day;

    return {
        dateTime : year + '-' + month + '-' + day,
        timeStamp : year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second,
        year : String(year),
        month : String(month),
        day : String(day),
        hour : String(hour),
        minute : String(minute),
        second : String(second)
    }
}

/*
    URL에 파라미터 붙이기
    (2020.06.13 / By.Chungwon)
*/
function setParamsToUrl(url, params){

    var data = "";
    if(empty(params) === false){

        for(var key in params){
            data += key + '=' + params[key] + '&';
        }
        data = data.indexOf('&') != -1 ?  data.slice(0, -1) : data;
    }
    url = url.indexOf('?') != -1 ? url : url + "?";

    // 마지막 & 삭제
    return url + data;
};
// 브라우저 중앙 위치 값 얻기
function getCenterFromPosition(width, height){
    var popupX = (window.screen.width / 2) - (width / 2);
    var popupY = (window.screen.height / 2) - (height / 2);

    return { x : popupX, y : popupY };
}
/* 
    팝업
*/
function popupOpen(url, title, _width, _height, positionX, positionY, zIndex, content){
    /* 
    toolbar = 상단 도구창 출력 여부 
    menubar = 상단 메뉴 출력 여부
    location = 메뉴아이콘 출력 여부
    directories = 제목 표시줄 출력 여부
    status = 하단의 상태바 출력 여부
    scrollbars = 스크롤바 사용 여부
    resizable = 팝업창의 사이즈 변경 가능 여부 
    */
    // 1일간 닫기 상태가 아닐 경우             
    var popUpUrl = url === null ? "" : url; //팝업창에 출력될 페이지 URL  
    var popUpOption = ""; //팝업창 옵션(optoin)

    var width = _width === null ? $('body').width() : _width;
    var height = _height === null ? $('body').height() : _height;
    // var height = _height === null ? 'auto' : _height;

    var currentBrowserSize = getCenterFromPosition(width, height);

    popUpOption += "width=" + width + ",";
    popUpOption += "height=" + height + ",";
    popUpOption += "left=" + positionY === null ? currentBrowserSize.y : positionY + ",";
    popUpOption += "top=" +  positionX === null ? currentBrowserSize.x : positionX + ",";
    popUpOption += "resizable=no,";
    popUpOption += "scrollbars=no,";
    popUpOption += "status=no";
    
    var popUpObject = window.open(popUpUrl, title, popUpOption);
    $(popUpObject).css('z-index', zIndex === null ? '10000' : zIndex);

    // 동적으로 내용 채워넣을 경우 window.open 메소드의 첫번째 파라미터는 비운다.
    if(content !== null && url === null){
        popUpObject.document.write(content);                
    }
};
// 아이디 / 비번 찾기
function goInfoFind(url){
    var width = 480;
    var height = 605;
    var popupPosition = getCenterFromPosition(width, height);
    var popupX = (window.screen.width / 2) - (width / 2);
    var popupY = (window.screen.height / 2) - (height / 2);

    // URL, Title, width, height, top, left, z-index, content(URL 대신 존재 시)
    popupOpen(url, "아이디 / 비밀번호 찾기", width, height, popupPosition.y, popupPosition.x, 1, null);
}
// 앞글자 대문자
function toUpperCaseCharAt(str) {
	return str.charAt(0).toUpperCase() + str.slice(1);
}
// 앞글자 소문자
function toLowerCaseCharAt(str) {
	return str.charAt(0).toLowerCase() + str.slice(1);
}
// 딕셔너리 배열 정렬 (ps. 정렬할 Array 요소의 개수가 2개 미만일 경우 ‘sort is not a function’ 오류가 난다. )
function dictionarySort(list, order, column){
    if(empty(list)){
        return false;
    }
    
    if(order === 'asc'){
        return list.sort(function(a, b) { // 오름차순
            return a[column] < b[column] ? -1 : a[column] > b[column] ? 1 : 0;
        });
    }else if(order === 'desc'){
        return list.sort(function(a, b) { // 오름차순
            return a[column] > b[column] ? -1 : a[column] < b[column] ? 1 : 0;
        });
    }
}
// 휴대폰 번호 유효성 검사 (2020.01.21 / By.Chungwon)
function vaildatePhoneNumber(number){
    number = number.replace('-', '');

    if(isNaN(number)){
        return false;
    }

    var regPhone = /(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/g;

    if(!regPhone.test(number)){
        return false;
    }
}
// 공백 체크 (탭이나 스페이스 포함)
function hasWhiteSpace(s) {
    return /\s/g.test(s);
}
// 특정 엘리먼트로 부드럽게 이동하기
function customFocus(target, duration, topOffset, callback){
    $("html, body").animate({
        scrollTop: target.offset().top + (empty(topOffset) ? 0 : topOffset)
    }, duration);

    if(!empty(callback)){
        callback();
    }

    // $(target)[0].scrollIntoView({ 
    //     behavior: 'smooth', 
    //     block: 'start'
    // });
}
/*
    실시간 검색 영역 설정
    (2020.06.25 / By.Chungwon)
*/
function setRealCont(selecter, callback, blur_method){
    // [엘리먼트] 영역 리스트
    var real = $('.real-search' + selecter);
    var $keyword = real.find('.real-keyword');
    
    real.find('.reset').on('click', function(e){
        var $real = $(e.currentTarget).closest('.real-search');
        var keyword = $real.find('.real-keyword');
        var real_key = $real.data('real_key');
        var $container = $("[data-container_type='" + real_key +"']");

        $container.html('');
        $container.removeClass('active');
        keyword.removeAttr('readonly');
        $real.removeClass('active');
    });

    $keyword.on('focus', function(e){
        var $real = $(e.currentTarget).closest('.real-search');
        var real_key = $real.data('real_key');
        var $container = $("[data-container_type='" + real_key +"']");
        var $select_item = $container.find('.real-item.active');

        if($select_item.find('.real-item').length > 0){
            $container.addClass('active');
        }
    });

    $keyword.on('blur', function(e){
        var $real = $(e.currentTarget).closest('.real-search');
        var real_key = $real.data('real_key');
        var $container = $("[data-container_type='" + real_key +"']");
        var $select_item = $container.find('.real-item.active');

        var selected_value = $select_item.find('[data-value]').data('value');

        if(empty(selected_value) === false){
            blur_method($real, $(e.currentTarget));
        }
        $container.removeClass('active');
    });

    $keyword.on('keyup', function(e){
        e.preventDefault();
        e.stopPropagation();

        var $real = $(e.currentTarget).closest('.real-search');
        var real_key = $real.data('real_key');
        var $container = $("[data-container_type='" + real_key +"']");
        $container.addClass('active');

        var key = e.keyCode;
        var keyword = $(e.currentTarget).val();
        
        // active 상태인 item 찾기
        var item = $container.find('.real-item');
        var $select_item = $container.find('.real-item.active');
        // active 상태값이 존재하는지 여부
        var selected_state = $select_item.length > 0 ? true : false;

        if( key == '40' || key == '38')
        {// 키보드 위아래 입력 시 (item - active 값 변경 목적)

            if(selected_state)
            {// active 상태값이 존재하는 경우
                if(key == 38)
                {// 위 클릭 (다음 이동)
                    if($select_item.prev('.real-item').length < 1){
                        return;
                    }
                    // 전체 active 삭제
                    $container.find('.real-item').removeClass('active');
                    // 선택 active 추가
                    $select_item.prev('.real-item').addClass('active');
                }else if(key == 40)
                {// 아래 클릭 (뒤로 이동)
                    if($select_item.next('.real-item').length < 1){
                        return;
                    }
                    // 전체 active 삭제
                    $container.find('.real-item').removeClass('active');
                    // 선택 active 추가
                    $select_item.next('.real-item').addClass('active');
                }
                $select_item = $container.find('.real-item.active');
            }else
            {// active 상태값이 존재하지 않는 경우

                // 전체 active 삭제
                $container.find('.real-item').removeClass('active');

                if(key == 38)
                {// 위 클릭 (맨 위로 이동 - active 삭제)
                    $container.find('.real-item:last').addClass('active');
                    
                }else if(key == 40)
                {// 아래 클릭 (첫번째 이동)
                    $container.find('.real-item:first').addClass('active');
                }
                
                $select_item = $container.find('.real-item.active');
            }
            var selected_value = $select_item.find('[data-value]').data('value');
            selected_value = empty(selected_value) ? "" : String(selected_value).trim();

            $(e.currentTarget).val(selected_value);

            // $('.top-search-cont > .search-ground-item').removeClass('active');
            // target.addClass('active');
            // target.focus();

            return;
        }else if(key == 13)
        {// 엔터키 입력 시 (현재 값에 active 처리 후, container 삭제)
            var selected_value = $select_item.find('[data-value]').data('value');
            selected_value = empty(selected_value) ? "" : String(selected_value).trim();
            $(e.currentTarget).val(selected_value);

            $container.removeClass('active');
            e.target.blur();
            return;
        }
        
        if(empty(keyword))
        {// 공백이나 빈 경우 컨테이너 닫고 return
            if($container.hasClass('active'))
            {
                $container.removeClass('active');
            }
            $container.find('.real-item').remove();
            $container.find('.none-data').remove();
        }else
        {// 검색 실행
            if($container.hasClass('active') === false)
            {
                $container.addClass('active');
            }

            callback(keyword, $container, real_key);
        }
    });

}

/*
    두 배열을 비교해서 중복되지 않는 값 반환
    앞의 배열이 기준이 된다.
    2020.06.29 / By.Chungwon
*/
function compareArrayResultNotDuplicate(arr1, arr2){
    var result_arr = [];

    for(var i = 0; i < arr1.length; i++){
        var insert_state = true;

        for(var j = 0; j < arr2.length; j++){
            if(Number(arr1[i]) === Number(arr2[j])){
                insert_state = false;
                break;
            }
        }
        if(insert_state){
            // 중복되지 않는 배열
            result_arr.push(arr1[i]);
        }
    }
    return result_arr;
}
// JS 개행을 HTML로 변환
function setTextarea(value){
    return value.replace(/(?:\r\n|\r|\n)/g, '<br />');
}
// 3차원 배열 데이터 값 얻기
function get3DimensionArrayData(list, column, value){
    for(var i = 0; i < list.length; i++){
        var item = list[i];

        if(item[column] === value){
            return item;
        }
    }
}
// summernote 에디터에 존재하는 이미지 목록 중 idx가 존재하는 이미지를 배열로 가져오기
function getSummerNoteImgList(img_list){
    var current_editor_image_idx = [];
    
    for (var i = 0; i < img_list.length; i++) 
    {// 현재 에디터의 이미지 목록
        var real_img = $(img_list[i]);
        var img_idx = real_img.data('filename');
        var src = real_img.attr('src');

        if(src.indexOf('http') != -1 || src.indexOf('data:image') != -1)
        {// 추가된 이미지 인 경우 생략
            // 참조형 이미지
            continue;
        }
        current_editor_image_idx.push(img_idx);
    }

    return current_editor_image_idx;
}

// 쿠키 생성
function setCookie(cName, cValue, cHours){
    var expire = new Date();
    expire.setTime(expire.getTime() + (cHours * 60 * 60 * 1000));
    // expire.setDate(expire.getDate() + cDay);
    cookies = cName + '=' + escape(cValue) + '; path=/ '; // 한글 깨짐을 막기위해 escape(cValue)를 합니다.
    if(typeof cDay != 'undefined') cookies += ';expires=' + expire.toGMTString() + ';';
    document.cookie = cookies;
}
// 쿠키 가져오기
function getCookie(cName) {
    cName = cName + '=';
    var cookieData = document.cookie;
    var start = cookieData.indexOf(cName);
    var cValue = '';
    if(start != -1){
        start += cName.length;
        var end = cookieData.indexOf(';', start);
        if(end == -1)end = cookieData.length;
        cValue = cookieData.substring(start, end);
    }
    return unescape(cValue);
}
// 쿠키 삭제하기
function deleteCookie(cName){
    localStorage.setItem(g_site_name + '_' + cName, "");
}

// 쿠키 배열로 가져오기 (2020.02.13 / By.Chungwon)
function getArrayCookie(cName){
    var cookie = localStorage.getItem(g_site_name + '_' + cName);

    return empty(cookie) || cookie === 'false' ? [] : JSON.parse(cookie);
}
// 쿠키 배열 추가하기 (2020.02.13 / By.Chungwon)
// key 입력 시 해당 컬럼은 중복 방지
function setArrayCookie(key_list, element, cName, cHours){
    var cookie = getArrayCookie(cName);

    if(empty(key_list) === false){
        // 중복 허용이 아닐 경우
        for(var i = 0; i < cookie.length; i++){
            var item = cookie[i];

            var duplicate_count = 0;

            for(var j = 0; j < key_list.length; j++){
                var key = key_list[j];

                if(element[key] == item[key]){
                    duplicate_count++;
                }
            }

            if(duplicate_count === key_list.length){
                cookie.splice(i, 1);
            }
        }
    }
    cookie.push(element);
    // 로컬 세션 세팅
    localStorage.setItem(g_site_name + '_' + cName, JSON.stringify(cookie));
}
// 쿠키 배열 요소 가져오기 (2020.02.13 / By.Chungwon)
function getArrayCookieElement(cName, key, value){
    var cookie = getArrayCookie(cName);

    return getItemFromKey(cookie, key, target);
}
// 쿠키 배열 요소 삭제하기 (2020.02.13 / By.Chungwon)
function deleteArrayCookieElement(cName, key, value, cHours){
    var cookie = getArrayCookie(cName);

    cookie = deleteDictionaryArray(cookie, key, value);
    setCookie(cName, JSON.stringify(cookie), cHours);
}
// Textarea 높이 자동 설정하기 (2020.05.06 / By.Chungwon)
function adjustHeight(target) {
    var textEle = $(target);

    textEle.each(function(){
        if($(this).data('origin_height') === undefined)
        {
            $(this).data('origin_height', $(this).height());
        }
        $(this).height(this.scrollHeight);    
    });
};


// smart editor
function getSmartEditor(selector, params, callback){
    var oEditors = [];
    var sLang = "ko_KR";
    
    // 생성 대상 엘리먼트가 꽉차게 존재하면 프레임이 넘친다. (참고 - https://m.blog.naver.com/PostView.nhn?blogId=monkeychoi&logNo=60171723220&proxyReferer=https%3A%2F%2Fwww.google.co.kr%2F)
    nhn.husky.EZCreator.createInIFrame({
        oAppRef: oEditors,
        elPlaceHolder: selector,
        sSkinURI: '/resources/editor/smarteditor/SmartEditor2Skin.html',  
        fCreator: "createSEditor2",
        fOnAppLoad : function(){
            // oEditors.getById[selector].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);

            // 에디터객체 리턴
            callback(oEditors);
        },
        htParams : {
            bUseToolbar : true,             // 툴바 사용 여부 (true:사용/ false:사용하지 않음)
            bUseVerticalResizer : true,     // 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
            bUseModeChanger : true,         // 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
            //bSkipXssFilter : true,        // client-side xss filter 무시 여부 (true:사용하지 않음 / 그외:사용)
            //aAdditionalFontList : aAdditionalFontSet,     // 추가 글꼴 목록
            fOnBeforeUnload : function(){
                //alert("완료!");
            },
            I18N_LOCALE : sLang
        }, //boolean
        customParams : params
    });
}

// smarteditor2의 IMG List 가져오기
function getEditorImgList(canvasId){
    var canvas = $('textarea[name=' + canvasId + ']').nextAll('iframe').contents();
    var canvasContent = canvas.find('#se2_iframe').contents();

    return canvasContent.find('img');
}
// 딕셔너리 키 값으로 서치 (배열, 키, 키값)
function getItemFromKey(dic, key, target){
    for(var i = 0 ; i < dic.length; i++){
        if(dic[i][key] == target){
            return dic[i];
        }
    }
}

// 날짜 계산
function compareDate(date1, date2){
    // date1이 클 경우 true, date2가 클 경우 false
    var value1 = date1.split('-');
    var value2 = date2.split('-');
    var dat1 = (Number(value1[0]) * 365) + (Number(value1[1]) * 30) + Number(value1[2]);
    var dat2 = (Number(value2[0]) * 365) + (Number(value2[1]) * 30) + Number(value2[2]);

    return dat1 > dat2;
}

function replaceAll(total_str, origin_str, new_str){
    var reg_exp = new RegExp(origin_str, "g"); // g = 대소문자 구분, gi = 대소문자 구분 없음

    return total_str.replace(reg_exp, new_str);
}

function replaceReverse(total_str, origin_str, new_str){
    // 전체 문자열을 반대로 뒤집는다.
    total_str = total_str.split("").reverse().join(""); 
    // replace 사용하기
    return total_str.replace(origin_str, new_str);
}










// 클립보드에 복사하기 (2020.09.03 / By.Chungwon)
function copyToClipboard(val) {
    var t = document.createElement("textarea");
    document.body.appendChild(t);
    t.value = val;
    t.select();
    document.execCommand('copy');
    document.body.removeChild(t);
}
// SNS 공유하기
function shareSNS(sns, strTitle, strURL, image) {
    var snsArray = new Array();
    var strMsg = strTitle + " " + strURL;
    var imageUrl = empty(image) ? "" : image;

    snsArray['twitter'] = "http://twitter.com/home?status=" + encodeURIComponent(strTitle) + ' ' + encodeURIComponent(strURL);
    snsArray['facebook'] = "http://www.facebook.com/share.php?u=" + encodeURIComponent(strURL);
    snsArray['pinterest'] = "http://www.pinterest.com/pin/create/button/?url=" + encodeURIComponent(strURL) + "&media=" + imageUrl + "&description=" + encodeURIComponent(strTitle);
    snsArray['band'] = "http://band.us/plugin/share?body=" + encodeURIComponent(strTitle) + "  " + encodeURIComponent(strURL) + "&route=" + encodeURIComponent(strURL);
    snsArray['blog'] = "http://blog.naver.com/openapi/share?url=" + encodeURIComponent(strURL) + "&title=" + encodeURIComponent(strTitle);
    snsArray['line'] = "http://line.me/R/msg/text/?" + encodeURIComponent(strTitle) + " " + encodeURIComponent(strURL);
    snsArray['pholar'] = "http://www.pholar.co/spi/rephol?url=" + encodeURIComponent(strURL) + "&title=" + encodeURIComponent(strTitle);
    snsArray['google'] = "https://plus.google.com/share?url=" + encodeURIComponent(strURL) + "&t=" + encodeURIComponent(strTitle);

    window.open(snsArray[sns]);
}
// 에디터(썸머노트) 사진 삽입 이벤트 연동 (2020.12.03 / By.Chungwon)
function createEditorForContent2(selecter, file_path){
    if(empty(g_table)){
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
                formData.append('ref_table', g_table);
                formData.append('ref_idx', "0");
                
                sendAPI("/common/file", "insertEditTempFile", formData, function(res)
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
function downloadExcel(_this, file_name, id_selector)
{/* 
    엑셀 다운로드 
    2020.12.05 / By.Chungwon - 
    jquery.battatech.excelexport.js 필요
*/
    // $("#" + id_selector).closest().find('[data-table]').each(function(index, element){
    //     var change_tag = $(element).data('table');
    //     var origin_content = $(element).html();

    //     $(element).replaceWith("<" + change_tag + ">" + origin_content + "</" + change_tag + ">");
    // });
    
    var uri = $("#" + id_selector).excelexportjs({
        containerid: id_selector
        , datatype: 'table'
        , returnUri: true
    });
    $(_this).attr('download', file_name + '.xls').attr('href', uri).attr('target', '_blank');
}
function niceBytes(x)
{// 숫자를 파일 단위로 변환
    var units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    var l = 0, n = parseInt(x, 10) || 0;

    while(n >= 1024 && ++l){
        n = n / 1024;
    }
    return(n.toFixed(n < 10 && l > 0 ? 1 : 0) + ' ' + units[l]);
}


function googleTranslateElementInit() 
{/* 
    구글 번역기 생성 (2020.12.18 / By.Chungwon)
*/
    if(g_page_init_complete)
    {
        var lang = localStorage.getItem("google_trans_lang");
        lang = empty(lang) ? "ko" : lang;
    
        // 구글 번역 상태 변경
        $("#lang_select option[value='" + lang + "']").prop("selected", true);

        // 구글 번역 이벤트 연동
        $("#lang_select").on('change', function(e){
            var lang = $(e.currentTarget).val();
            googleTranslateChange(lang);
        });

        // document.cookie ="googtrans=" + lang;

        new google.translate.TranslateElement({
            pageLanguage: lang, 
            includedLanguages: 'ko,en,zh-CN,zh-TW,zh-HK,ja',
            autoDisplay : false,
            multilanguagePage : true,
        }, "google_translate_element");
        

    }else
    {// 페이지 로딩이 끝날때까지 반복
        setTimeout(function(){
            googleTranslateElementInit();
        }, 100);
    }
}
function googleTranslateChange(lang)
{/* 
    구글 번역 언어 변경 이벤트 (2020.12.14 / By.Chungwon)
*/ 
    var select = document.querySelector("#google_translate_element select");
        // var val = current_language;
        
    if(lang === 'zh'){
        lang = 'zh-CN'; 
    }
    else if(lang !== 'ko' 
        && lang !== 'en' 
        && lang !== 'zh-CN' 
        && lang !== 'zh-TW' 
        && lang !== 'zh-HK' 
        && lang !== 'ja')
    {
        lang = 'en';
    }

    if(empty(select) === false)
    {
        for (var i = 0; i < select.options.length; i++) 
        {
            if (select.options[i].value == lang) 
            {
                select.selectedIndex = i;
                break;
            }
        }
        localStorage.setItem("google_trans_lang", lang);
        select.dispatchEvent(new Event('change'));    
    }
}
function calculationCommissionRate(currency, commission_rate, price, num)
{/* 
    수수료율 계산 (2020.12.16 / By.Chungwon)
*/
    num = empty(num) ? 1 : num;
    commission_rate = Number(commission_rate);
    price = Number(price);

    var result_price = (commission_rate / 100) * price;
    return currency + (result_price).toFixed(num);
}
function createRandomString(count, is_num)
{/* 
    문자열 난수 생성 (2020.12.17 / By.Chungwon)
*/
    var text = "";
    var possible = "!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    count = empty(count) ? 8 : count;
    is_num = empty(is_num) ? true : is_num;
    possible = is_num ? possible + "0123456789" : possible;;

    for( var i = 0; i < count; i++)
    {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}
function checkOnlyAlpha(str)
{/* 
    영문 확인 (2020.12.18 / By.Chungwon)
*/

    var check_num = /[0-9]/; 
    var check_eng = /[a-zA-Z]/; 
    var check_spc = /[~!@#$%^&*()_+|<>?:{}]/; 
    var check_kor = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/; // 한글체크

    if( check_eng.test(str) && !check_kor.test(str)) 
    { 
        return true; 
    }
    else{ 
        return false; 
    }
}




///////////////////////////// 아이더스 ///////////////////////
function downloadSdk(is_devloper)
{// SDK 다운로드
    if(is_devloper)
    {// 개발자인 경우
        // 다운로드
        location.href = "/view/developer/page/sdk_download.php";
    }
    else
    {// 사용자거나 비회원인 경우
        // 개발자 회원가입 화면으로
        alert("Only developers can access.");
        location.href = "/view/developer/page/login.php";
    }
}
function downloadSdk1(is_devloper)
{// SDK 다운로드
    var link = "<a id='temp_link' href='/resources/file/Aidusgun_UNITY SDK_Plugin.zip' download></a>";
    $("html").append(link);
    
    if(is_devloper)
    {// 개발자인 경우
        // 다운로드
        $("#temp_link")[0].click();
        $("#temp_link").remove();
    }
    else
    {// 사용자거나 비회원인 경우
        // 개발자 회원가입 화면으로
        location.href = "/developer/page/login.php";
    }
}
function downloadSdk2(is_devloper)
{// SDK 다운로드
    var link = "<a id='temp_link' href='/resources/file/Aidusgun_UNITY SDK_Plugin.zip' download></a>";
    $("html").append(link);
    
    if(is_devloper)
    {// 개발자인 경우
        // 다운로드
        $("#temp_link")[0].click();
        $("#temp_link").remove();
    }
    else
    {// 사용자거나 비회원인 경우
        // 개발자 회원가입 화면으로
        location.href = "/developer/page/login.php";
    }
}


function createLink(link) 
{// JS로 링크 다운로드 (2021.07.30 / By.Chungwon)
    var link_selector = "temp_download";
    var link_element = StringFormat("<a id='{0}' href='{1}' download></a>", link_selector, link);
    $("html").append(link_element);

    $("#" + link_selector)[0].click();
    $("#" + link_selector).remove();
}

function deleteStrFromEnd(str, index)
{// 뒤에서부터 문자열 삭제하기 (2021.07.30 / By.Chungwon)
    return str.slice(0, str.length - index);
}

function betweenDate(start_date, target_date, end_date)
{// 기준일이 시작, 마감일 사이에 포함되는지 여부 (2021.08.02 / By.Chungwon)
    // date1이 클 경우 true, date2가 클 경우 false
    var start_value = start_date.split('-');
    start_value = (Number(start_value[0]) * 365) + (Number(start_value[1]) * 30) + Number(start_value[2]);

    var target_date = target_date.split('-');
    target_date = (Number(target_date[0]) * 365) + (Number(target_date[1]) * 30) + Number(target_date[2]);

    var end_date = end_date.split('-');
    end_date = (Number(end_date[0]) * 365) + (Number(end_date[1]) * 30) + Number(end_date[2]);

    if((start_value <= target_date) && (target_date < end_date))
    {// 기준일이 시작일보다 크거나 같고, 마감일보다 작은 경우 true
        return true;
    }
    return false;
}

function createExcelHead(selector)
{// 엑셀 폼을 위한 헤더 자동 생성 (2021.08.04 / By.Chungwon)

    var result = {
        default : "",
        key : "",
        type : "",
        intro : "",
        name : "",
    }
    $(selector).each(function(index, element){
        var default_value = $(element).data('excel_default');
        default_value = empty(default_value) ? "" : default_value;

        result['default'] += StringFormat("<td>{0}</td>", default_value);
        result['key'] += StringFormat("<td>{0}</td>", $(element).data('excel_key'));
        result['type'] += StringFormat("<th>{0}</th>", $(element).data('excel_type'));
        result['intro'] += StringFormat("<th>{0}</th>", $(element).data('excel_intro'));
        result['name'] += StringFormat("<th>{0}</th>", $(element).data('excel_name'));
    });

    return result;
}
function htmlEscape(a)
{// 이스케이프 처리 (2021.08.14 / By.Chungwon)
    return a.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/\"/g, "&quot;");
}
function htmlUnescape(a)
{// 이스케이프 해제 (2021.08.14 / By.Chungwon)
    return a.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, "\"");
}
function getParams(key)
{// URL에서 파라미터 가져오기 (2021.08.19 / By.Chungwon)
    if(empty(location.href.match(key + '=([^&]*)')) === false)
    {
        return location.href.match(key + '=([^&]*)')[1];
    }
    return false;
}