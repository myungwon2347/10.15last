<?php	
	namespace service;

	$api_name = 'file';
	$target_name = "파일";
	$response_params = array();
	$action = $_REQUEST['action'];

	require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

	use util\Log;
	use util\File;
/********************************************************************************************************************************************/



/************************************** SELECT LIST **************************************/
if($action === 'insertEditTempFile')
{	/*
		에디터 임시 파일 등록
		2020.06.29 / By.Chungwon
	*/

	// [접근권한] 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }

	$type = paramVaildCheck("type", NULL);
	$ref_table = paramVaildCheck("ref_table", NULL);
	$ref_idx = paramVaildCheck("ref_idx", NULL);


	/************************************ 파일 업로드 ************************************/
	foreach($_FILES as $img_key => $files) 
	{// 수신한 파일 동적처리
		// 이미지 존재 확인
		$img_list = File::setFiles($files);

		if(isset($img_list))
		{			
			$file_info_list = array();
			$ref_key = $img_key;

			// 유효성 검사 (파일크기 (24mb), 포맷 검사)
			$img_error_msg = File::errorCheck($img_list, 24, array('zip', 'png', 'jpg', 'txt', 'ppt', 'pptx', 'pdf', 'doc', 'hwp', 'xlsx','jpeg','gif','bmp','BMP','JPG','JPEG','PNG','GIF', 'webp', 'WEBP', 'jfif', 'heic', 'JFIF', 'HEIC'));
			if(apiErrorCheck(gettype($img_error_msg) != "string", "{$img_error_msg}")){ return; }
			// 파일 DB 등록
			$img_insert_idx_list = File::insert($img_list, $type, $ref_table, $ref_key, $ref_idx);

			if(empty($img_insert_idx_list))
			{ 
				return; 
			}else
			{// ex. detail_iil - detail insert idx list
				$img_insert_idx_list = explode(',', $img_insert_idx_list);

				for($i = 0; $i < count($img_insert_idx_list); $i++)
				{// 등록된 파일 idx 루핑
					$insert_idx = $img_insert_idx_list[$i];
					if(empty($insert_idx) || $insert_idx === "") { continue; }

					$file_info = Files::getFromIdx($insert_idx);

					array_push($file_info_list, $file_info);					
				}
			}
			$response_params['file_info_list'] = $file_info_list;
		}
	}
	/************************************ 파일 업로드 끝 ************************************/
}
else if($action === 'getList')
{	/*
		파일 목록 조회
		2020.06.29 / By.Chungwon
	*/
	$type = paramVaildCheck("type", NULL);
	$ref_table = paramVaildCheck("ref_table", NULL);
	$ref_key = paramVaildCheck("ref_key", NULL);
	$ref_idx = paramVaildCheck("ref_idx", NULL);

	if(apiErrorCheck(isset($ref_idx), "게시판 식별정보가 없습니다.\n고객센터에 문의주세요.")){ return; }

	$file_list = Files::getList($type, $ref_table, $ref_key, $ref_idx);
	
	/************************** 값 리턴 **************************/
    $response_params['gdata'] = $file_list;
}

else if($action === "updateHit")
{	/*	조회수 증가
		2020.07.25 / By.Chungwon
	*/

	/************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	// if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	// if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
	$target_idx = paramVaildCheck("{$api_name}_idx", NULL);

    /************************************************* 필터링 *************************************************/
	if(apiErrorCheck($target_idx, "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* DB - UPDATE *************************************************/
	$response_params['update_state'] = Files::updateHit($target_idx);
	if(apiErrorCheck($response_params['update_state'], "{$target_name} 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
}
/********************************************************************************************************************************************/
http_response_code(200);
echo json_encode($response_params);