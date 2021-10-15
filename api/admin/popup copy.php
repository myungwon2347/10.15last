<?php	
	namespace service;

	$api_name = 'popup';
    $target_name = "팝업";
	$response_params = array();
	$action = $_REQUEST['action'];

    use util\File;
    use util\Log;
    use util\Visit;
    use service\Files;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

/*****************************************************************************************************************************************************************************/
if($action === "")
{// 액션 파라미터가 비어있는 경우
	if(apiErrorCheck(true, "액션 파라미터가 없습니다.\n고객센터에 문의주세요.")){ return; }
}
/**************************************************************************** INSERT & UPDATE ****************************************************************************/
else if($action === "upload")
{   /*	등록 및 수정
		2020.07.22 / By.Minseo
	*/
    /************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
    if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }
    
    /************************************************* 파라미터 수신 *************************************************/
    $target_idx = paramVaildCheck("{$api_name}_idx", NULL);
    // 단일 데이터
    $title = paramVaildCheck("title", NULL);
    $view_state = paramVaildCheck("view_state", 1);
    $start_date = paramVaildCheck("start_date", "");
    $end_date = paramVaildCheck("end_date", NULL);
    $render_type = paramVaildCheck("render_type", NULL);
    $device_type = paramVaildCheck("device_type", NULL);
    $z_index = paramVaildCheck("z_index", NULL);    
    $size_x = paramVaildCheck("size_x", NULL);    
    $size_y = paramVaildCheck("size_y", NULL);
    $location_x = paramVaildCheck("location_x", NULL);
    $location_y = paramVaildCheck("location_y", NULL);
    
    // 등록될 에디터 이미지
    $editor_upload_image_idx = isset($_REQUEST['editor_upload_image_idx']) ? $_REQUEST['editor_upload_image_idx'] : NULL;
    // 삭제 리스트
    $delete_file_idx_list = paramVaildCheck("delete_file_idx_list", NULL);

    /************************************************* 필터링 *************************************************/
    // if(apiErrorCheck(isset($title) || $title !== "", "제목을 입력해주세요.")){ return; }
    // if(apiErrorCheck(isset($content) || $content !== "", "내용을 입력해주세요.")){ return; }

    /************************************************* DB - 분기 처리 *************************************************/
	if(isset($target_idx) && $target_idx !== "null")
	{// 수정
		$update_state = Popup::update($is_admin, $user_common_idx, $target_idx, $title, $view_state, $start_date, $end_date, $render_type, $device_type, $z_index, $size_x, $size_y, $location_x, $location_y);
        if(apiErrorCheck($update_state, "{$target_name} 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}else
	{// 등록
		$target_idx = Popup::insert($title, $view_state, $start_date, $end_date, $render_type, $device_type, $z_index, $size_x, $size_y, $location_x, $location_y);
    }
    /************************************************* 파일 업로드 *************************************************/
    // 파일 변경 데이터
	$file_update_list = isset($_REQUEST['file_update_list']) ? json_decode($_REQUEST['file_update_list'], true) : NULL;

    foreach($_FILES as $img_key => $files) 
	{// 수신한 파일 동적처리
		// 이미지 존재 확인
		$img_list = File::setFiles($files);

		if(isset($img_list))
		{   // 아래 4개는 꼭 입력해주세요.
			$file_type = "image";
			$ref_table = $api_name;
			$ref_key = $img_key;
			$ref_idx = $target_idx;
			
			// 유효성 검사 (파일크기 (24mb), 포맷 검사)
			$img_error_msg = File::errorCheck($img_list, 24, array('png', 'jpg', 'jpeg', 'gif'));
			if(apiErrorCheck(gettype($img_error_msg) != "string", "{$img_error_msg}")){ return; }
			// 파일 DB 등록
			$img_insert_idx_list = File::insert($img_list, $file_type, $ref_table, $ref_key, $ref_idx);
			if(empty($img_insert_idx_list))
			{ 
				return; 
			}else
            {// 파일 등록 성공 . ex. detail_iil - detail insert idx list
				$response_params[$img_key . '_iil'] = $img_insert_idx_list;
			}
		}
    }
    // 파일 값 일부 변경 (order_num, value)
    $file_list = Files::getList("image", $api_name, "common", $target_idx)['list'];
    for($i = 0; $i < count($file_update_list); $i++)
    {
        $file = $file_update_list[$i];

        for($j = 0; $j < count($file_list); $j++)
        {
            if($file_list[$j]['o_name'] === $file['o_name'])
            {
                $update_state = Files::updateName($file['o_name'], $file['order_num'], $file['type'], $file['ref_table'], $file['ref_key'], $target_idx, $file['value']);
                if(apiErrorCheck($update_state, "{$target_name} 파일 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
    
                break;
            }
        }

    }
    /************************************************* 파일 업로드 끝 *************************************************/

	/************************************************* 기존 파일 삭제 *************************************************/
    // 파일 삭제 리스트
    $delete_file_idx_list = explode(',', paramVaildCheck("delete_file_idx_list", NULL));

    // [데이터베이스] DELETE (파일) */
    for($i = 0; $i < count($delete_file_idx_list); $i++)
    {
        $delete_idx = $delete_file_idx_list[$i];
        if(empty($delete_idx) || $delete_idx === ""){ continue; }

		// [데이터베이스 & 서버] 파일 삭제
        $delete_status = Files::delete($delete_idx, $user_common_idx, $is_admin);
        if(apiErrorCheck($delete_status, "파일 삭제 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
    }
	/************************************************* 기존 파일 삭제 끝 *************************************************/

    $response_params["{$api_name}_idx"] = $target_idx;
}
/**************************************************************************** UPDATE ****************************************************************************/
else if($action === "update")
{	/*	데이터 수정
		2020.07.25 / By.Chungwon
	*/

	/************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
	$target_idx = paramVaildCheck("{$api_name}_idx", NULL);
	// 단일 데이터
    $title = paramVaildCheck("title", NULL);
    $view_state = paramVaildCheck("view_state", "1");
    $start_date = paramVaildCheck("start_date", "");
    $end_date = paramVaildCheck("end_date", NULL);
    $render_type = paramVaildCheck("render_type", NULL);
    $device_type = paramVaildCheck("device_type", NULL);
    $img_count = paramVaildCheck("img_count", NULL);
    $z_index = paramVaildCheck("z_index", NULL);    
    $size_x = paramVaildCheck("size_x", NULL);    
    $size_y = paramVaildCheck("size_y", NULL);
    $location_x = paramVaildCheck("location_x", NULL);
    $location_y = paramVaildCheck("location_y", NULL);

    /************************************************* 필터링 *************************************************/
	if(apiErrorCheck($target_idx, "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* DB - UPDATE *************************************************/
	$response_params['update_state'] = Popup::update($is_admin, $user_common_idx, $target_idx, $title, $view_state, $start_date, $end_date, $render_type, $device_type, $img_count, $z_index, $size_x, $size_y, $location_x, $location_y);
	if(apiErrorCheck($response_params['update_state'], "{$target_name} 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
}
/**************************************************************************** DELETE ****************************************************************************/
else if($action === "delete")
{   /*	데이터 삭제
        2020.07.24 / By.Chungwon
    */

    /************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
	$target_idx = paramVaildCheck("{$api_name}_idx", NULL);

    /************************************************* 필터링 *************************************************/
	if(apiErrorCheck($target_idx, "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* DB - DELETE *************************************************/
    $response_params['delete_state'] = Popup::delete($target_idx, $is_admin, $user_common_idx);
    if(apiErrorCheck($response_params['delete_state'], "{$target_name} 데이터 삭제 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
    
    /************************** DELETE 된 파일 모두 삭제 **************************/
    $file_list = Files::getList(NULL, $api_name, NULL, $target_idx)['list'];
    for($i = 0; $i < count($file_list); $i++)
    {
        $file = $file_list[$i];
        Files::delete($file['idx'], $target_idx, true);
    }
}
/**************************************************************************** SELECT DETAIL ****************************************************************************/
else if($action === "get")
{   /*
		상세정보 조회
		2020.07.15 / By.Chungwon
	*/

	/************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	// if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
    $target_idx = paramVaildCheck("{$api_name}_idx", NULL);
    
    /************************************************* 필터링 *************************************************/
	if(apiErrorCheck($target_idx, "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* DB - SELECT DETAIL *************************************************/
	$data_info = Popup::get($target_idx, $user_common_idx);
	if(apiErrorCheck($data_info, "{$target_name} 상세정보 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* 리턴 *************************************************/
	$response_params['data_info'] = $data_info;
}
/**************************************************************************** SELECT LIST ****************************************************************************/
else if($action === "getList")
{   /*
        목록 조회
        2020.07.22 / By.Minseo
    */
	/************************************************* 접근권한 *************************************************/
    // 로그인 유저만 접근 가능
	// if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	// if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
    // 페이징 및 정렬
	$limit = paramVaildCheck("data_render_count", 20);
	$offset = (paramVaildCheck("page_selected_idx", 1) - 1) * $limit;
    $sort_list = paramVaildCheck("sort_list", "idx desc");

    // 검색 필터    
    $title = paramVaildCheck("title", "");
    $view_state = paramVaildCheck("view_state", "0,1");
    $start_date = paramVaildCheck("start_date", "");
    $end_date = paramVaildCheck("end_date", "");
    $device_type = paramVaildCheck("device_type", "1,2,3");
    $action_flag = paramVaildCheck("action_flag", "");
    
    /************************************************* 검색 필터 - 분기 *************************************************/    
    if($is_common || $action_flag === "user")
    {// 일반유저
        $view_state = "1";
        $device_name = Visit::getUserDeviceType() === "PC" ? 1 : 2;
        $device_type = $device_name . ",3";
    }

    /************************************************* 필터링 *************************************************/    
    // if(apiErrorCheck(isset($ref_idx), "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* DB - SELECT LIST *************************************************/
    $data_info = Popup::getList($action_flag, $title, $view_state, $start_date, $end_date, $device_type, $sort_list, $limit, $offset);
    if(apiErrorCheck($data_info, "{$target_name} 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* 리턴 *************************************************/
    $response_params['data_list'] = $data_info['list'];
    $response_params['data_count'] = $data_info['count'];
}
else if($action === 'getFileList')
{	/*
		파일 목록 조회
		2020.07.15 / By.Chungwon
	*/
	/************************************************* 접근권한 *************************************************/
    // 로그인 유저만 접근 가능
	// if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	// if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
    // 페이징 및 정렬
	// $limit = paramVaildCheck("data_render_count", 20);
	// $offset = (paramVaildCheck("page_selected_idx", 1) - 1) * $limit;
    // $sort_list = paramVaildCheck("sort_list", "insert_date desc");

    // 검색 필터    
    $type = paramVaildCheck("type", NULL);
	$ref_table = paramVaildCheck("ref_table", $api_name);
	$ref_key = paramVaildCheck("ref_key", NULL);
    $ref_idx = paramVaildCheck("{$api_name}_idx", NULL);
    
    /************************************************* 검색 필터 - 분기 *************************************************/    
    // if($is_common)
    // {// 일반유저
    //     $device_type_state = 1;
    // }

    /************************************************* 필터링 *************************************************/    
    // if(apiErrorCheck(isset($ref_idx), "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* DB - SELECT LIST *************************************************/
    $data_info = Files::getList($type, $ref_table, $ref_key, $ref_idx);
    if(apiErrorCheck($data_info, "{$target_name} 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    $response_params['data_list'] = $data_info['list'];
    $response_params['data_count'] = $data_info['count'];
}
/*****************************************************************************************************************************************************************************/
http_response_code(200);
echo json_encode($response_params);








