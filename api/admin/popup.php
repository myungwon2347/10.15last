<?php	
	namespace service;
	/*
		관리자 - 팝업 컨트롤러
		2021.09.28 / By. Chungwon
	*/
    use util\DB;
	use util\Log;
	use util\File;
	use service\Files;
	

    /************************************************* 페이지 정보 세팅 *************************************************/
	require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    $response_params = array();
	$action = $_REQUEST['action'];

    /************************************************* API 정보 세팅 *************************************************/
	$target_flag = "Popup"; 			// 편의상 API의 ID 키
	$target_table = 'popup'; 			// 실제 DB 테이블 명
	$target_idx_name = "popup_idx"; 	// 데이터 PK 키 이름
    $target_name = "팝업"; 				// API 명명 (로그 및 출력용)
    
	/************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	if(apiErrorCheck($is_admin, "관리자 유저만 접근 가능합니다.")) { return; }

	
	
	
/*****************************************************************************************************************************************************************************/
if($action === "")
{// 액션 파라미터가 비어있는 경우
	if(apiErrorCheck(true, "액션 파라미터가 없습니다.\n고객센터에 문의주세요.")){ return; }
}
/**************************************************************************** INSERT & UPDATE ****************************************************************************/
else if($action === "upload{$target_flag}")
{   /*	
		등록
		2021.09.28 / By.Chungwon

		요청페이지: 관리자 > 팝업 등록
    */    
    /************************************************* 요청 파라미터 수신 및 파싱 *************************************************/
	// 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name, "order_num", "view_status",  // 공통 데이터 값
		"title", "start_date", "end_date", "width", "height", "location_x", "location_y", // 프로젝트 추가 값
		"view_type", "device_type", // 확장성 고려
	));
    // 검색 변수 중, 숫자 값 넣기
    $request['numberList'] = array("order_num", "view_status", "width", "height", "location_x", "location_y");
    // 테이블 메인 값 설정
	$request['target_idx'] = isset($request[$target_idx_name]) ? $request[$target_idx_name] : NULL;
    /************************************************* 요청 파라미터 수신 및 파싱 끝 *************************************************/
	


    /************************************************* 필터링 *************************************************/
	if(apiErrorCheck(isset($request['title']) && $request['title'] !== "", "제목 값이 누락됐습니다.")) { return; }
	if(apiErrorCheck(isset($request['start_date']) && $request['start_date'] !== "", "게시일 값이 누락됐습니다.")) { return; }
	if(apiErrorCheck(isset($request['end_date']) && $request['end_date'] !== "", "종료일 값이 누락됐습니다.")) { return; }
	if(apiErrorCheck(isset($request['width']) && $request['width'] !== "", "너비 값이 누락됐습니다.")) { return; }
	if(apiErrorCheck(isset($request['height']) && $request['height'] !== "", "높이 값이 누락됐습니다.")) { return; }
	if(apiErrorCheck(isset($request['location_x']) && $request['location_x'] !== "", "x좌표 값이 누락됐습니다.")) { return; }
	if(apiErrorCheck(isset($request['location_y']) && $request['location_y'] !== "", "y좌표 값이 누락됐습니다.")) { return; }
    /************************************************* 필터링 끝 *************************************************/



	/************************************************* DB - 분기 처리 *************************************************/
	unset($request[$target_idx_name]); // 수신 키 제거 (ex. board_idx)

	if(isset($request['target_idx']) && $request['target_idx'] !== "null")
	{/********** UPDATE 로직 **********/ 
		
		$update_state = Popup::action("update", $request);
		if(apiErrorCheck($update_state, "{$target_name} 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}else
	{/********** INSERT 로직 **********/ 

		$request['reg_user_idx'] = $user_idx;

		$request['target_idx'] = Popup::action("insert", $request);
		if(apiErrorCheck($request['target_idx'], "{$target_name} 데이터 등록 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}
    /************************************************* DB - 분기 처리 끝 *************************************************/
	

	/************************************************* 파일 업로드 *************************************************/
    $res_image_status = false;
	// 파일 변경 데이터
	$file_update_list = isset($_REQUEST['file_update_list']) ? json_decode($_REQUEST['file_update_list'], true) : NULL;


	foreach($_FILES as $img_key => $files) 
	{// 수신한 파일 동적처리
		// 이미지 존재 확인
		$img_list = File::setFiles($files);

		if(isset($img_list))
		{   // 아래는 꼭 입력해주세요.
			$file_type = "image";
			$ref_table = $target_table;
			$ref_key = $img_key;
			$ref_idx = $request['target_idx'];
			
			// 유효성 검사 (파일크기 (24mb), 포맷 검사)
			// $img_error_msg = File::errorCheck($img_list, 24, array('png', 'jpg', 'jpeg', 'gif'));
			$img_error_msg = File::errorCheck($img_list, 24, array('zip', 'png', 'jpg', 'txt', 'ppt', 'pptx', 'pdf', 'doc', 'hwp', 'xlsx','jpeg','gif','bmp','BMP','JPG','JPEG','PNG','GIF', 'webp', 'WEBP', 'jfif', 'heic', 'JFIF', 'HEIC'));
			if(apiErrorCheck(gettype($img_error_msg) != "string", "{$img_error_msg}")){ return; }
			// 파일 DB 등록
			$img_insert_idx_list = File::insert($img_list, $file_type, $ref_table, $ref_key, $ref_idx);

            
            if(empty($img_insert_idx_list))
			{ 
				return; 
			}else
            {// ex. detail_iil - detail insert idx list
                if($ref_key === 'thumbnail' && $res_image_status === false)
                {// 썸네일 인 경우

                    // 대표 이미지 세팅
                    // Product::update($request['target_idx'], null, null, null, null, null, null, null, null, null, null, null, null, explode(",", $img_insert_idx_list)[0], null, null, null);
                    
                    $res_image_status = true;
                }
                $response_params[$img_key . '_iil'] = $img_insert_idx_list;
			}

			// 파일 값 일부 변경 (order_num, value)
			$file_list = Files::getList($file_type, $ref_table, $ref_key, $request['target_idx'])['list'];
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
        $delete_status = Files::delete($delete_idx, $request['target_idx'], $is_admin);
        // if(apiErrorCheck($delete_status, "파일 삭제 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
    }
	/************************************************* 기존 파일 삭제 끝 *************************************************/


	
	/************************************************* 결과 값 리턴 *************************************************/
	$response_params["target_idx"] = $request['target_idx'];
}












/**************************************************************************** DELETE ****************************************************************************/
else if($action === "delete{$target_flag}")
{   /*
		삭제
		2021.09.28 / By.Chungwon

		요청페이지: 관리자 > 포트폴리오 목록
	*/
	/************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }

	/************************************************* 파라미터 수신 및 파싱 *************************************************/
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name
	));

    // 테이블 메인 값 설정
	$request['target_idx'] = isset($request[$target_idx_name]) ? $request[$target_idx_name] : NULL;

	/************************************************* 필터링 *************************************************/
	if(apiErrorCheck(isset($request['target_idx']) && $request['target_idx'] !== "", "식별 값이 누락됐습니다.")) { return; }
	
	/************************************************* DB - 분기 처리 *************************************************/
	// 중복 데이터 삭제
	$delete_status = Popup::action("delete", array(
		'where_query' => "
			idx = {$request[$target_idx_name]}
		",
	));

	/************************************************* 결과 값 리턴 *************************************************/
	$response_params['delete_state'] = $delete_status;
}











/**************************************************************************** SELECT DETAIL ****************************************************************************/
else if($action === "get{$target_flag}")
{   /*
		상세 조회
		2021.09.14 / By.Chungwon
	*/

    /************************************************* 요청 파라미터 수신 및 파싱 *************************************************/
	// 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name,
	));
	$request['target_idx'] = $request[$target_idx_name];
	$request['reg_user_idx'] = $user_idx;
    /************************************************* 요청 파라미터 수신 및 파싱 끝 *************************************************/



    /************************************************* 필터링 *************************************************/
	// if(apiErrorCheck(isset($request[$target_idx_name]), "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }
    /************************************************* 필터링 끝 *************************************************/



	/************************************************* DB - SELECT DETAIL ***************************************/
	$action = $action . "Admin";
	$sql_result = Popup::$action($request);
    if(apiErrorCheck($sql_result, "{$target_name} 상세 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	/************************************************* DB - SELECT DETAIL 끝 ***************************************/



    /************************************************* 리턴 **************************************************/
	$response_params['data_list'] = $sql_result;
}











/**************************************************************************** SELECT LIST ****************************************************************************/
else if($action === "getList{$target_flag}")
{   /*
        목록 조회
        2021.09.28 / By.Chungwon
    */

    /************************************************* 요청 파라미터 수신 및 파싱 *************************************************/
	$request = getListFromTotal($_REQUEST, array(
		"view_status", "insert_date_start", "insert_date_end",   // 공통 데이터 값
		"title",  // 프로젝트 추가 값
		"view_type", // 확장성 고려
	));

	$request['limit'] = intval(paramVaildCheck("data_render_count", 20));
	$request['offset'] = (intval(paramVaildCheck("page_selected_idx", 1)) - 1) * $request['limit'];
    $request['sort_list'] = paramVaildCheck("sort_list", "idx desc");
    /************************************************* 요청 파라미터 수신 및 파싱 끝 *************************************************/


	
	/************************************************* DB - SELECT LIST *************************************************/
	$action = $action . "Admin";
    $sql_result = Popup::$action($request);
    if(apiErrorCheck($sql_result, "{$target_name} 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* 리턴 *************************************************/
    $response_params['data_list'] = $sql_result['list'];
    $response_params['data_count'] = $sql_result['count'];
}



else if($action === "getFileList{$target_flag}")
{	/*
		파일 목록 조회
		2021.09.28 / By.Chungwon
	*/

	/************************************************* 파라미터 수신 *************************************************/

	// 검색 필터    
	$request = getListFromTotal($_REQUEST, array(
		"type", "ref_table", "ref_key", "target_idx"
	));
	$request['type'] = empty($request['type']) ? "" : $request['type'];
	$request['ref_key'] = empty($request['ref_key']) ? "" : $request['ref_key'];
	$request['ref_table'] = empty($request['ref_table']) ? "" : $target_table;

	/************************************************* 검색 필터 - 분기 *************************************************/    

	/************************************************* 필터링 *************************************************/    
	if(apiErrorCheck(isset($request['target_idx']), "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }

	/************************************************* DB - SELECT LIST *************************************************/
	$data_info = Files::getList($request['type'], $request['ref_table'], $request['ref_key'], $request['target_idx']);
	if(apiErrorCheck($data_info, "{$target_name} 파일 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

	$response_params['data_list'] = $data_info['list'];
	$response_params['data_count'] = $data_info['count'];
}

/**************************************************************************** UTIL ****************************************************************************/
/********************************************************************************************************************************************/
else{
	if(apiErrorCheck(true, "액션 파라미터가 없습니다.\n고객센터에 문의주세요.")){ return; }
}
http_response_code(200);
echo json_encode($response_params);


