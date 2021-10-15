<?php
namespace service;

$api_name = 'visiter';
$target_name = '방문자';
$response_params = array();
$action = $_REQUEST['action'];

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

/********************************************************************************************************************************************/

/*
    유저
*/
if($action === 'getVisitInfo')
{   // 방문자 통계 정보 조회 (2020.07.10 / By.Chungwon)

	/************************************************* 접근권한 *************************************************/
    // 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
    // 페이징 및 정렬
	$data_render_count      =   paramVaildCheck("data_render_count", 20);        // 게시물 렌더링 수
    $page_render_count      =   paramVaildCheck("page_render_count", 5);         // 페이지 렌더링 수
    $page_selected_idx      =   paramVaildCheck("page_selected_idx", 1);         // 현재 페이지 (쪽)
    $page_selected_sheet    =   paramVaildCheck("page_selected_sheet", 1);       // 현재 페이지 (장)
    
    $limit = $data_render_count;
    $offset = ($page_selected_idx - 1) * $limit;
    $sort_list = paramVaildCheck("sort_list", "reg_date desc");

    // 검색 필터    
    // [파라미터] 검색 */
    $ip                     =   paramVaildCheck("ip", "");
    $referer                =   paramVaildCheck("referer", "");
    $current_url            =   paramVaildCheck("current_url", "");
    $current_url2            =   paramVaildCheck("current_url2", "");
    $start_date             =   paramVaildCheck("start_date", "2020-01-01"); 
    $end_date               =   paramVaildCheck("end_date", date("Y-m-d")); 
    $device_type            =   paramVaildCheck("device_type", "");
    $device_name            =   paramVaildCheck("device_name", "");
    $browser                =   paramVaildCheck("browser", "");
    $is_revisit             =   paramVaildCheck("is_revisit", "");
    

    /************************************************* DB - SELECT LIST *************************************************/
    $data_info = Visiter::getVisitInfo($ip, $referer, $current_url, $current_url2, $start_date, $end_date, $device_type, $device_name, $browser, $is_revisit, $sort_list, $limit, $offset);
    if(apiErrorCheck($data_info, "{$target_name} 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* 리턴 *************************************************/
    $response_params['data_list'] = $data_info['list'];
    $response_params['data_count'] = $data_info['count'];
}
else if($action === 'getVisitInfoAll')
{   // 모든 방문자 통계 정보 조회 (2020.07.10 / By.Chungwon)

	/************************************************* 접근권한 *************************************************/
    // 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
    // 페이징 및 정렬
	$data_render_count      =   paramVaildCheck("data_render_count", 20);        // 게시물 렌더링 수
    $page_render_count      =   paramVaildCheck("page_render_count", 5);         // 페이지 렌더링 수
    $page_selected_idx      =   paramVaildCheck("page_selected_idx", 1);         // 현재 페이지 (쪽)
    $page_selected_sheet    =   paramVaildCheck("page_selected_sheet", 1);       // 현재 페이지 (장)
    
    $limit = $data_render_count;
    $offset = ($page_selected_idx - 1) * $limit;
    $sort_list = paramVaildCheck("sort_list", "reg_date desc");

    // 검색 필터    
    // [파라미터] 검색 */
    $ip                     =   paramVaildCheck("ip", "");
    $referer                =   paramVaildCheck("referer", "");
    $current_url            =   paramVaildCheck("current_url", "");
    $current_url2            =   paramVaildCheck("current_url2", "");
    $start_date             =   paramVaildCheck("start_date", "2020-01-01"); 
    $end_date               =   paramVaildCheck("end_date", date("Y-m-d")); 
    $device_type            =   paramVaildCheck("device_type", "");
    $device_name            =   paramVaildCheck("device_name", "");
    $browser                =   paramVaildCheck("browser", "");

    /************************************************* DB - SELECT LIST *************************************************/
    $data_info = Visiter::getVisitInfoAll($ip, $referer, $current_url, $current_url2, $start_date, $end_date, $device_type, $device_name, $browser, $sort_list, $limit, $offset);
    if(apiErrorCheck($data_info, "{$target_name} 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* 리턴 *************************************************/
    $response_params['data_list'] = $data_info['list'];
    $response_params['data_count'] = $data_info['count'];
}
else if($action === 'getVisitStatics')
{	// 방문자 통계 조회 (2020.04.02 / By.Chungwon)

    // if(isset($_SESSION[$GLOBALS['SITE']['E_NAME']]['login_user']) === false && $_SESSION[$GLOBALS['SITE']['E_NAME']]['login_user']['auth_level'] !== 'admin')
    if(isset($_SESSION['login_user']) === false && $_SESSION['login_user']['auth_level'] !== 'admin')

    {// 관리자가 아닌 경우 필터링
        if(apiErrorCheck(false, "올바른 접근이 아닙니다.\n고객센터에 문의주세요.")){ return; }
    }

	/*********************  비즈니스 로직 *********************/
	// 1. 통계 가져오기
	$re_visit 		    = 	Visiter::reVisiterCount(null, true);
	$real_visit 		= 	Visiter::reVisiterCount(true, true);

    $today_visit 	 	= 	Visiter::getVisitStatics(0, true);
	$yesterday_visit	= 	Visiter::getVisitStatics(1, true);
	$total_visit 		= 	Visiter::getVisitStatics(null);
	
    if(apiErrorCheck($today_visit, "방문자 통계 조회 중 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
    if(apiErrorCheck($re_visit, "방문자 통계 조회 중 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    $response_params['today_visit'] = $today_visit;
    $response_params['yesterday_visit'] = $yesterday_visit;
    $response_params['real_visit'] = $real_visit;
    $response_params['re_visit'] = $re_visit;
    $response_params['total_visit'] = $total_visit;
}
else if($action === 'getVisitLog')
{	// 방문자 수 조회 (2020.04.02 / By.Chungwon)

    // if(isset($_SESSION[$GLOBALS['SITE']['E_NAME']]['login_user']) === false && $_SESSION[$GLOBALS['SITE']['E_NAME']]['login_user']['auth_level'] !== 'admin')
    if(isset($_SESSION['login_user']) === false && $_SESSION['login_user']['auth_level'] !== 'admin')

    {// 관리자가 아닌 경우 필터링
        if(apiErrorCheck(false, "올바른 접근이 아닙니다.\n고객센터에 문의주세요.")){ return; }
    }

    $day_count = paramVaildCheck("day_count", 0);

	$visit_count_list = Visiter::getVisitLog($day_count);

	if(apiErrorCheck($visit_count_list, "방문자 수 조회 중 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    $response_params['visit_count_list'] = $visit_count_list;
}

/********************************************************************************************************************************************/
http_response_code(200);
echo json_encode($response_params);