<?php	
namespace util; 

require_once "{$_SERVER['DOCUMENT_ROOT']}/config.php";

class DB
{
	// DB 
	public static function Execute($query, $is_found_rows = false)
	{
		// DB 로그인
		$con = new \mysqli($GLOBALS['DB']['HOST'], $GLOBALS['DB']['ID'], $GLOBALS['DB']['PW'], $GLOBALS['DB']['NAME']);
		
		// DB 에러 핸들링
        if ($con->connect_errno)
		{
			Log::write("Failed to connect to MySQL: {$con->connect_error}", "error");
			return false;
		}

		// RETURN 값
		$return_value = false;

		// Usage across all PHP versions
		// $query = get_magic_quotes_gpc() ? addslashes($query) : $query;		
		// $query = $con->real_escape_string($query);

		// SQL 실행
		$result = $con->query($query);
		
		if ($result)
		{	// SQL 성공
			$action = substr(trim($query), 0, 6);

			if(stripos($action, "select") !== false)
			{	// SELETE 쿼리인 경우
				$result_list = array();

				if (mysqli_num_rows($result) > 0)
				{
					while($row = $result->fetch_assoc()) 
					{
						array_push($result_list, $row);
					}
				}
				$return_value = $result_list;
				$result->free();
				// SQL 로깅 
				Log::write($query, "database", "select");

			}else{	// INSERT, UPDATE, DELETE 쿼리인 경우				
				if(stripos($action, "insert") !== false){
					// INSERT 인 경우 idx 반환
					$return_value = $con->insert_id;
				}else{
					// UPDATE, DELETE 인 경우
					$return_value = true;
				}
				// SQL 로깅 
				Log::write($query, "database", "action");
			}

			if($is_found_rows)
			{// 페이징 처리가 필요한 로직
				$count = $con -> query("SELECT FOUND_ROWS() AS count") -> fetch_assoc()['count'];

				return array(
					"list" => $return_value,
					"count" => $count
				);
			}

		}else	
		{	// SQL 오류
			Log::write("{$query}\n[DB Error Content] - ".$con->error, "error");
		}
		
		if ($con) {
			$con->close();
		}

		return $return_value;
	}





	






	/************************** 액션 모듈 **************************/
	function createWhereQuery($request, $item_list)
	{/*
		SQL - WHERE Query 생성
		2021.08.28 / By.Chungwon
		
        $item_list = array(
			"text" => array( // 쿼리 타입 (필수)
				array(
					"key"					=> "id",	// DB 컬럼명
					"name"					=> "sender_idx",		// 파라미터에 매핑되는 값 (선택-기본값 key)
					"defalut"				=> "",		// 기본 값 (선택-기본값 "") 
					"is_number"				=> "",		// 기본 값 (선택-기본값 false) 
				),
				array(
					"key"					=> "work_list",
					"defalut"				=> "",
				),
			),
			"regexp" => array(
				array(
					"key"					=> "status",
					"defalut"				=> "",
				),
			),
			"date" => array(
				array(
					"key"					=> "latest_start_date",
					"defalut"				=> "",
				),
			),
		)
    */
			
		$result_list = array();

		foreach ($item_list as $type => $value_list) 
		{
			for($j = 0; $j < count($value_list); $j++)
			{
				$item = $value_list[$j];

				$defalut 	= empty($item['defalut']) 		? 	"" 			: $item['defalut']; // defalut 기본 값 설정
				$key 		= empty($item['key']) 			? 	"" 			: $item['key']; 	  // key 기본 값 설정
				$name 		= empty($item['name']) 			? 	$key 		: $item['name']; 	  // name 기본 값 설정
				$is_number 	= empty($item['is_number']) 	? 	false 		: $item['is_number']; 	  // is_number 기본 값 설정
				// $is_zero 	= empty($item['is_zero']) 		? 	false 		: $item['is_zero']; 	  // is_zero 기본 값 설정
				
				$value = isset($request[$name]) ? $request[$name] : "";
				
				if($type === "like_space")
				{// 포함된 문자열 모두 검색 (공백까지 모두 검색)
					empty($value) 		? 	$defalut 		: array_push($result_list, "
						AND (`{$key}` LIKE '%{$value}%' OR REPLACE(`{$key}`, ' ', '') LIKE '%{$value}%')
					");
				}
				else if($type === "equal")
				{// 키랑 값이 완벽하게 동일한 경우
					$result_value = $is_number ? $value : "'{$value}'";
					$value === "" 		? 	$defalut 		: array_push($result_list, "
						AND `{$key}` = {$result_value}
					");
				}
				else if($type === "in")
				{// 값이 포함된 경우 (쉼표로 구분, 전부 쌍따옴표로 감싸도 같은 결과임)
					$result_value = array();
					$opt_list = $value === "" ? array() : explode(",", $value);

					for($i = 0; $i < count($opt_list); $i++)
					{
						$temp_value = $opt_list[$i];
						$temp_value = $is_number ? $temp_value : "'{$temp_value}'";
						array_push($result_value, $temp_value);
					}
					$value_join = join(",", $result_value);

					$value === "" 		? 	$defalut 		: array_push($result_list, "
						AND `{$key}` IN ({$value_join})
					");
				}
				
				else if($type === "regexp")
				{// 포함된 문자열 모두 검색
					empty($value) 		? 	$defalut 		: array_push($result_list, "
						AND `{$key}` REGEXP REPLACE('{$value}', ',', '|')
					");
				}
				else if($type === "date_range")
				{// 날짜 검색 (컬럼만 전달하기. 컨트롤러에서 start, end 값 전달 필수)
					$interval_day 	= empty($item['interval_day']) 	? 	"1" 		: $item['interval_day']; 	  // interval_day 기본 값 설정

					$value_start = empty($request[$key . "_start"]) 		? 	$defalut 		: $request[$key . "_start"];
					$value_end 	= empty($request[$key . "_end"]) 			? 	$defalut 		: $request[$key . "_end"];

					empty($value_start) 		? 	$defalut 		: array_push($result_list, "
						AND DATE('{$value_start}') <= `{$key}`
					");
					empty($value_end) 		? 	$defalut 		: array_push($result_list, "
						AND DATE_ADD(DATE('{$value_end}'), INTERVAL {$interval_day} DAY) > `{$key}`
					");
				}				
			}
        }
		
		return join("", $result_list);
	}

	public static function action($datas)
	{/* 액션 쿼리 실행 (2021.06.04 / By.Chungwon)
		
		[파라미터]
		datas = 2차원 해쉬맵 배열 (numberList라는 키가 배열 형태로 되어 있으며 필수값임.)
			예시)
				$datas = array(
					'action' => 'insert',   // 필수값 - 액션명 (insert, update, delete)
					'table' => "board',   // 필수값 - 테이블명
					'numberList' => NULL,   // 필수값 (INSERT, UPDATE)
        			'target_idx' => 5,   // 필수값 (UPDATE, DELETE)

					'type' => 1,
					'reference_table' => "etst",
				);

				DB::action($datas);

		모듈 호출 전, 값은 파싱해서 전달해야 합니다.
	*/

		// 데이터 값 중, 액션 값 추출하기
		$action = $datas['action'];
		unset($datas['action']);
		// 데이터 값 중, 테이블 값 추출하기 
		$table = $datas['table'];
		unset($datas['table']);
		// 데이터 값 중, 숫자 항목들 추출하기
		$numberList = empty($datas['numberList']) ? NULL : $datas['numberList'];
		unset($datas['numberList']);

		// 데이터 값 중, IDX 항목 추출하기
		$target_idx = isset($datas['target_idx']) ? $datas['target_idx'] : null;
		unset($datas['target_idx']);

		// WHERE 커스텀
		$where_query = isset($datas['where_query']) ? $datas['where_query'] : "idx = {$target_idx}";
		unset($datas['where_query']);
		
		/******* INSERT 쿼리 생성 *******/
		$sql = "";
		// 컬럼 목록 (등록용)
		$column_list = array();
		// 값 목록 (등록용)
		$values_list = array();
		// 컬럼과 값 목록 (수정용)
		$update_list = array();

        foreach ($datas as $key => $value) {
			// 컬럼 추가
			array_push($column_list, "`{$key}`");
			// 현재 컬럼이 숫자 목록에 존재하는 지 확인
			$is_number = isset($numberList) && in_array($key, $numberList);
			// 값 추가  (문자인 경우, 작은 따옴표 입력)
			array_push($values_list, $is_number ? $value : "'{$value}'");
			// 컬럼과 값 추가
			array_push($update_list, "`{$key}` = " . ($is_number ? "{$value}" : "'{$value}'"));
        }

		$column_list = join("\n	,	", $column_list);
		$values_list = join("\n	,	", $values_list);
		$update_list = join("\n	,	", $update_list);
		/******* INSERT 쿼리 생성 끝 *******/

		if(stripos($action, "insert") !== false)
		{// INSERT인 경우 쿼리 실행 후 결과값 리턴
			$sql = "
INSERT INTO `{$table}`
(
	{$column_list}
) 
VALUES
(
	{$values_list}
)";
		}
		else if(stripos($action, "update") !== false)
		{// UPDATE인 경우 쿼리 실행 후 결과값 리턴
			$sql = "
UPDATE `{$table}` SET
	{$update_list}
	,	update_date = NOW()
WHERE
	{$where_query}
	";
		}
		else if(stripos($action, "delete") !== false)
		{// DELETE인 경우 쿼리 실행 후 결과값 리턴
			$sql = "
DELETE FROM `{$table}`
WHERE
	{$where_query}
	";
		}
		return DB::Execute($sql);
	}















	public static function isDuplicate($table, $key, $value, $other = "")
	{// 중복 검사
		return DB::Execute("
			SELECT 
				COUNT(*) as count
			FROM
				`{$table}`
			WHERE
				{$key} = {$value}
				{$other}
		")[0]['count'] > 0 ? true : false;
	}	
	public static function increaseHit($table, $target_idx, $request = null)
	{// 조회수 증가 (2021.07.17 / By.Chungwon)

		// 자신의 글은 조회수 증가 안함
		$reg_user_idx_sql = empty($request) || empty($request['reg_user_idx']) ? "" : "AND reg_user_idx != {$request['reg_user_idx']}";
		$where_sql = empty($request) || empty($request['where_sql']) ? "" : "{$where_sql}";
		
		return DB::Execute("
			UPDATE `{$table}` SET
				hit = hit + 1 
			WHERE
				idx = {$target_idx}
				{$reg_user_idx_sql}
				{$where_sql}
		");
	}
}
